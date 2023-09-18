<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classs;
use App\Models\course;
use App\Models\kind;
use App\Models\subject;
use App\Models\comment;
use App\Models\suggestion;
use App\Models\objection;
use App\Models\user_course;
use Illuminate\Http\Request;
use NunoMaduro\Collision\Adapters\Phpunit\Subscribers\Subscriber;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'role_id' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required',
            'phone_no' => 'required',
        ]);
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = $request->role_id;
        $user->password = bcrypt($request->password);
        $user->phone_no = $request->phone_no;
        $user->badget = 0;
        $user->blocked = false;
        $user->img_url = '';
        $user->save();

        $accessToken = $user->createToken('authToken')->accessToken;

        return response([
            'status' => true,
            'user' => $user,
            'message' => 'User Created Successfully',
            'access_token' => $accessToken
        ]);
    }

    public function login(Request $request)
    {

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response(['status' => false, 'message' => 'Invalid User'], 404);
        }
        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(
            [
                'status' => true,
                'user' => auth()->user(),
                'access_token' => $accessToken
            ],
            200
        );
    }

    public function ShowTeachers()
    {
        $var = User::where('role_id', 2)->get(['name', 'email', 'phone_no', 'img_url']);
        return response([
            'status' => true,
            'Teachers' => $var
        ], 200);
    }

    public function profile()
    {
        $var = auth()->user();
        $c = User::where('users.id', auth()->user()->id)
            ->join('user_courses', 'user_courses.user_id', 'users.id')
            ->join('courses', function ($join) {
                $join->on('courses.id', 'user_courses.course_id')
                    ->on('courses.classes_num', '>=', 'user_courses.level');
            })
            ->join('kinds', 'courses.kind_id', 'kinds.id')
            ->leftjoin('classses', 'classses.course_id', 'courses.id')
            ->where('index', 1)
            ->get([
                'courses.id as course_id', 'kinds.name as kind_name', 'price',
                'courses.name as course_name', 'courses.disc', 'language',
                'level', 'classes_num', 'subscribers', 'likes', 'classses.id as class_id',
                'dislikes', 'courses.rating as course_rating', 'courses.img_url',
                'user_courses.rating as student_rating', 'level', 'like', 'dislike'
            ]);
        $f = User::where('users.id', auth()->user()->id)
            ->join('user_courses', 'user_courses.user_id', 'users.id')
            ->join('courses', function ($join) {
                $join->on('courses.id', 'user_courses.course_id')
                    ->on('courses.classes_num', '<', 'user_courses.level');
            })
            ->join('kinds', 'courses.kind_id', 'kinds.id')
            ->get([
                'courses.id as course_id', 'kinds.name as kind_name', 'price',
                'courses.name as course_name', 'disc', 'language',
                'level', 'classes_num', 'subscribers', 'likes',
                'dislikes', 'courses.rating as course_rating', 'courses.img_url',
                'user_courses.rating as student_rating', 'level', 'like', 'dislike'
            ]);
        if (auth()->user()->role_id == 2) {
            $m = Course::where('user_id', auth()->user()->id)
                ->join('kinds', 'courses.kind_id', 'kinds.id')
                ->get([
                    'courses.id as course_id', 'kinds.name as kind_name', 'price',
                    'courses.name as course_name', 'disc', 'language',
                    'classes_num', 'subscribers', 'likes', 'dislikes',
                    'courses.rating', 'courses.img_url',
                ]);
            return response([
                'status' => true,
                'Profile_Info' => $var,
                'My_courses' => $m,
                'Current_courses' => $c,
                'Finished_courses' => $f
            ], 200);
        }

        return response([
            'status' => true,
            'Profile_Info' => $var,
            'Current_courses' => $c,
            'Finished_courses' => $f
        ], 200);
    }

    public function search(Request $request)
    {
        if ($request->value == '*') {
            $cor1 = course::where('visible', 1)
                ->where('ended', 1)
                ->orderBy('rating', 'desc')->get();
            return response([
                'status' => true,
                'Results' => $cor1
            ], 200);
        }
        if (Subject::where('name', $request->value)->exists()) {
            $cor2 = Subject::where('subjects.name', $request->value)
                ->join('kinds', 'kinds.subject_id', 'subjects.id')
                ->join('courses', 'courses.kind_id', 'kinds.id')
                ->where('courses.visible', 1)
                ->where('ended', 1)
                ->get();
            return response([
                'status' => true,
                'Results' => $cor2
            ], 200);
        }
        $cor3 = course::where('courses.visible', 1)
            ->where('ended', 1)
            ->where('name', 'LIKE', '%' . $request->value . '%')->get();
        return response([
            'status' => true,
            'Results' => $cor3
        ], 200);
    }
    public function BuyCourse(Request $request)
    {
        if (!course::where('id', request()->course_id)->exists()) {
            return response([
                'status' => true,
                'message' => 'Unfound course'
            ], 200);
        }
        $user = User::find(auth()->user()->id);
        $cr = course::find(request()->course_id);
        if (user_course::where('user_id', auth()->user()->id)->where('course_id', request()->course_id)->exists()) {
            return response([
                'status' => true,
                'message' => 'you already have this course'
            ], 200);
        }
        if ($user->badget >= $cr->price) {
            $new = new user_course();
            $new->user_id = auth()->user()->id;
            $new->course_id = request()->course_id;
            $new->level = 1;
            $new->rating = 0;
            $new->like = false;
            $new->dislike = false;
            $new->save();
            $user->badget -= $cr->price;
            $cr->subscribers += 1;
            $cr->save();
            $user->save();
            return response([
                'status' => true,
                'message' => 'Buy completed successfully'
            ], 200);
        } else
            return response([
                'message' => 'The purchase has not been completed Because you do not have enough balance'
            ], 200);
    }
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response([
            'status' => true,
            'message' => "User logged out successfully"
        ], 200);
    }
    public function DeleteAccount(Request $request)
    {
        User::where('id', auth()->user()->id)->delete();
        return response([
            'status' => true,
            'Results' => "Account Deleted Successfully"
        ], 200);
    }
    public function showCourse(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);
        if (!course::where('id', request()->id)->exists())
            return response([
                'status' => false,
                'message' => "unfound course"
            ], 200);
        $course = Course::where('courses.id', request()->id)
            ->join('kinds', 'courses.kind_id', 'kinds.id')
            ->join('subjects', 'subjects.id', 'kinds.subject_id')
            ->join('users', 'courses.user_id', 'users.id')
            ->get([
                'courses.id as course_id', 'kinds.name as kind_name', 'subjects.name as subject_name',
                'courses.name as course_name', 'courses.disc', 'courses.language',
                'courses.classes_num', 'courses.subscribers', 'users.name as Teacher_name',
                'courses.user_id as Teacher_id', 'courses.likes', 'courses.dislikes',
                'courses.price', 'courses.rating', 'courses.img_url',
            ]);
        $var = user_course::where('course_id', request()->id)->get(['id']);
        $arr  = array();
        foreach ($var as $v) {
            array_push($arr, $v['id']);
        }
        $comments = Comment::whereIn('user_course_id', $arr)
            ->join('user_courses', 'comments.user_course_id', 'user_courses.id')
            ->join('users', 'user_courses.user_id', 'users.id')
            ->get(['users.name', 'comments.comment']);
        return response([
            'status' => true,
            'Results' => $course,
            'comments' => $comments,
        ], 200);
    }
    public function AddSuggestion(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        $sug = new suggestion();
        $sug->name = $request->name;
        $sug->save();
        return response([
            'status' => true,
            'message' => "Suggestion sent Successfully ."
        ], 200);
    }
    public function Addobjection(Request $request)
    {
        $obj = new objection();
        $obj->name = $request->name;
        $obj->user_id = auth()->user()->id;
        $obj->save();
        return response([
            'status' => true,
            'message' => "Objection sent Successfully ."
        ], 200);
    }
    public function home()
    {
        $sub = subject::get(['name']);
        $cor = course::where('visible', 1)
            ->join('users', 'courses.user_id', 'users.id')
            ->join('kinds', 'courses.kind_id', 'kinds.id')
            ->join('subjects', 'kinds.subject_id', 'subjects.id')
            ->orderBy('rating', 'desc')
            ->get([
                'courses.id as course_id', 'courses.name as course_name',
                'courses.user_id as teacher_id', 'users.name as teacher_name',
                'kinds.name as kind_name', 'courses.kind_id as kind_id',
                'subjects.name as subject_name', 'subjects.id as subject_id',
                'classes_num', 'price', 'courses.disc', 'language',
                'subscribers', 'likes', 'dislikes', 'rating', 'courses.img_url'
            ]);
        $var = user::where('id', 'a')->get();
        foreach ($cor as $c) {
            $var->push(user::where('id', $c['teacher_id'])->get());
        }
        $var = collect($var)->unique()->all();
        return response([
            'status' => true,
            'subjects' => $sub,
            'top courses' => $cor,
            'top teachers' => $var
        ], 200);
    }
    public function finishCourse(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'course_id' => 'required',
        ]);
        $var = user_course::where('user_courses.user_id', $request->student_id)
            ->where('course_id', $request->course_id)
            ->join('courses', 'courses.id', 'user_courses.course_id')
            ->get([
                'courses.name as course_name', 'courses.id as course_id',
                'courses.user_id as teacher_id',
                'level', 'courses.classes_num'
            ]);
        $var2 = User::where('id', $var[0]['teacher_id'])->get();
        $var3 = User::where('id', $request->student_id)->get(['name', 'img_url']);
        if ($var[0]['level'] >= $var[0]['classes_num'])
            return response([
                'status' => 1,
                'message' => 'The student finished the course',
                'Course' => $var[0],
                'student' => $var3[0],
                'teacher_name' => $var2[0]['name'],
            ], 200);
        else
            return response([
                'status' => 0,
                'message' => 'The student hasn\'t finished the course yet'
            ], 200);
    }
}
