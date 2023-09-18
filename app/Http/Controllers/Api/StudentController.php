<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\user_course;
use App\Models\course;
use App\Models\comment;
use App\Models\classs;
use App\Models\question;
use App\Models\notification;

class StudentController extends Controller
{
    public function AddComment(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'comment' => 'required',
        ]);
        if (!course::where('id', request()->course_id)->exists())
            return response([
                'status' => false,
                'message' => "unfound course"
            ], 200);
        $var = user_course::where('user_id', auth()->user()->id)->where('course_id', request()->course_id)->get();
        if (count($var) == 0)
            return response([
                'status' => false,
                'message' => "Sorry, you can't put a comment before buying the course ."
            ], 200);
        $comment = new comment();
        $comment->comment = $request->comment;
        $comment->user_course_id = $var[0]['id'];
        $comment->save();
        return response([
            'status' => true,
            'message' => "Comment Added Successfully ."
        ], 200);
    }
    public function like(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
        ]);
        if (!course::where('id', request()->course_id)->exists())
            return response([
                'message' => "unfound course"
            ], 200);
        $var1 = user_course::where('user_id', auth()->user()->id)->where('course_id', request()->course_id)->get();
        $var2 = user_course::find($var1[0]['id']);
        if (count($var1) == 0)
            return response([
                'message' => "Sorry, you can't like before buying the course ."
            ], 200);
        $var = course::find($request->course_id);
        if ($var1[0]['like'] == 1) {
            $var->likes -= 1;
            $var->save();
            $var2->like = 0;
            $var2->save();
            return response([
                'status' => true,
            ], 200);
        }
        $var->likes += 1;
        $var->save();
        $var2->like = 1;
        $var2->save();
        if ($var1[0]['dislike'] == 1) {
            $var->dislikes -= 1;
            $var->save();
            $var2->dislike = 0;
            $var2->save();
        }
        return response([
            'status' => true,
            'message' => 'like added'
        ], 200);
    }
    public function dislike(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
        ]);
        if (!course::where('id', request()->course_id)->exists())
            return response([
                'message' => "unfound course"
            ], 200);
        $var1 = user_course::where('user_id', auth()->user()->id)->where('course_id', request()->course_id)->get();
        $var2 = user_course::find($var1[0]['id']);
        if (count($var1) == 0)
            return response([
                'message' => "Sorry, you can't like before buying the course ."
            ], 200);
        $var = course::find($request->course_id);
        if ($var1[0]['dislike'] == 1) {
            $var->dislikes -= 1;
            $var->save();
            $var2->dislike = 0;
            $var2->save();
            return response([
                'status' => true,
            ], 200);
        } else
            $var->dislikes += 1;
        $var->save();
        $var2->dislike = 1;
        $var2->save();
        if ($var1[0]['like'] == 1) {
            $var->likes -= 1;
            $var->save();
            $var2->like = 0;
            $var2->save();
        }
        return response([
            'status' => true,
            'message' => 'dislike added'
        ], 200);
    }

    public function AddNotification(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'name' => 'required',
        ]);
        if (!user_course::where('course_id', $request->course_id)
            ->where('user_id', auth()->user()->id)->exists())
            return response([
                'status' => 0,
                'message' => "you dont have this course"
            ], 200);
        $not = new notification();
        $not->name = $request->name;
        $not->user_id = auth()->user()->id;
        $not->course_id = $request->course_id;
        $not->save();
        return response([
            'status' => 1,
            'message' => "objection sent Successfully ."
        ], 200);
    }

    public function AddRating(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'rating' => 'required',
        ]);
        $var = user_course::where('user_id', auth()->user()->id)->where('course_id', request()->course_id)->get();
        if (count($var) == 0)
            return response([
                'message' => "Sorry, you can't rate a course before buying the course ."
            ], 200);
        if ($request->rating > 5 || $request->rating < 0)
            return response([
                'message' => "The rating must be between zero and five ."
            ], 200);
        $var = user_course::find($var[0]['id']);
        $var->rating = $request->rating;
        $var->save();
        return response([
            'status' => true,
            'message' => "rating Added successfully"
        ], 200);
    }
    public function ShowClass(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'class_id' => 'required',
        ]);
        if (!classs::where('id', $request->class_id)
            ->where('course_id', $request->course_id)->exists())
            return response([
                'status' => 0,
                'message' => "wrong input"
            ], 200);
        if (!User_course::where('user_id', auth()->user()->id)
            ->where('course_id', $request->course_id)->exists())
            return response([
                'status' => 0,
                'message' => "You did not buy this course"
            ], 200);
        $var = User_course::where('user_courses.user_id', auth()->user()->id)
            ->where('course_id', $request->course_id)->get();
        $class = Classs::where('id', $request->class_id)->get();
        if ($var[0]['level'] < $class[0]['index'])
            return response([
                'status' => 0,
                'message' => "You did not reach this class yet"
            ], 200);
        $classes = Classs::where('course_id', $request->course_id)->get(['id', 'course_id', 'name', 'disc', 'index']);
        $ques = question::where('class_id', $request->class_id)->get();
        return response([
            'status' => 1,
            'classes' => $classes,
            'this_class' => $class,
            'ques' => $ques
        ], 200);
    }
    public function SendAnswers(Request $request)
    {
        $request->validate([
            'class_id' => 'required',
            'answers' => 'required',
        ]);
        $ques = question::where('class_id', $request->class_id)->get(['correct_answer']);
        $arr = array();
        for ($i = 0; $i < count($ques); $i++) {
            array_push($arr, (int) $ques[$i]['correct_answer']);
        }
        $false_answers = array_diff_assoc($arr, $request->answers);
        $false_answers = array_keys($false_answers);
        if (count($false_answers) == 0) {
            $var = Classs::where('id', $request->class_id)->get(['index', 'course_id']);
            $var2 = Classs::where('index', ($var[0]['index'] + 1))->get(['id']);
            $lev = User_course::where('course_id', $var[0]['course_id'])
                ->where('user_id', auth()->user()->id)->get('id');
            $lev2 = user_course::find($lev[0]['id']);
            $lev2->level += 1;
            $lev2->save();
            if (count($var2) == 0)
                return response([
                    'status' => 1,
                    'message' => 'All true , congratulation you complete the course',
                ], 200);
            return response([
                'status' => 1,
                'message' => 'All true',
                'next_class_id' => $var2[0]['id']
            ], 200);
        }
        $arr2 = array();
        for ($i = 0; $i < count($false_answers); $i++) {
            array_push($arr2, (int) ($false_answers[$i] + 1));
        }
        return response([
            'status' => 0,
            'message' => 'You have wrong answers',
            'your_wrong_answers' => $arr2
        ], 200);
    }
}
