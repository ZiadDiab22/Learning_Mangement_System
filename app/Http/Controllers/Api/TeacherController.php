<?php

namespace App\Http\Controllers\Api;

use App\Models\classs;
use App\Models\User;
use App\Models\course;
use App\Http\Controllers\Controller;
use App\Models\question;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function AddCourse(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'language' => 'required',
            'img_url' => 'required',
            'kind_id' => 'required',
            'kind_id' => 'required',
            'price' => 'required'
        ]);
        if (auth()->user()->role_id == 3)
            return response([
                'status' => 0,
                'message' => 'Sorry , This process is for teachers only .',
            ], 200);

        $course = new course();
        $course->user_id = auth()->user()->id;
        $course->kind_id = request()->kind_id;
        $course->disc = request()->disc;
        $course->language = request()->language;
        $course->name = request()->name;
        $course->price = request()->price;
        $course->subscribers = 0;
        $course->likes = 0;
        $course->classes_num = 0;
        $course->dislikes = 0;
        $course->rating = 0;
        $course->ended = false;
        $course->visible = true;
        $course->img_url = request()->img_url;
        $course->save();

        return response([
            'status' => true,
            'course' => $course,
            'message' => 'Course Added Successfully , Thank you for sharing in building our community .',
        ], 200);
    }
    public function AddClasss(Request $request)
    {
        if (auth()->user()->role_id != 2)
            return response([
                'status' => 0,
                'message' => 'Sorry , This process is for teachers only .',
            ], 200);
        $request->validate([
            'course_id' => 'required',
        ]);
        if (!course::where('id', $request->course_id)->where('user_id', auth()->user()->id)->exists())
            return response([
                'status' => 0,
                'message' => 'you dont have this course',
            ], 200);
        $var = classs::where('course_id', $request->course_id)->count();
        $classs = new classs();
        $classs->course_id = request()->course_id;
        $classs->disc = 0;
        $classs->name = 0;
        $classs->index = $var + 1;
        $classs->video_url = 0;
        $classs->save();
        $var = course::find($request->course_id);
        $var->classes_num += 1;
        $var->save();
        return response([
            'status' => 1,
            'class' => $classs,
            'message' => 'Added Successfully',
        ], 200);
    }
    public function AddClasssData(Request $request)
    {
        if (auth()->user()->role_id != 2)
            return response([
                'status' => 0,
                'message' => 'Sorry , This process is for teachers only .',
            ], 200);
        $request->validate([
            'class_id' => 'required',
            'disc' => 'required',
            'name' => 'required',
            'video_url' => 'required',
            'questions' => 'required',
            'choices' => 'required',
            'correctAnswers' => 'required'
        ]);
        $classs = classs::find($request->class_id);
        $classs->disc = request()->disc;
        $classs->name = request()->name;
        $classs->video_url = request()->video_url;
        $classs->save();
        $i = 0;
        $j = 0;
        foreach ($request->questions as $req1) {
            $ques = new question();
            $ques->question = $req1;
            $ques->class_id = $request->class_id;
            $k = 0;
            if (!isset($request->choices[$j][$k])) $ques->ch1 = 0;
            else $ques->ch1 = $request->choices[$j][$k];
            $k++;
            if (!isset($request->choices[$j][$k])) $ques->ch2 = 0;
            else $ques->ch2 = $request->choices[$j][$k];
            $k++;
            if (!isset($request->choices[$j][$k])) $ques->ch3 = 0;
            else $ques->ch3 = $request->choices[$j][$k];
            $k++;
            if (!isset($request->choices[$j][$k])) $ques->ch4 = 0;
            else $ques->ch4 = $request->choices[$j][$k];
            $ques->correct_answer = $request->correctAnswers[$i++];
            $j++;
            $ques->save();
        }
        return response([
            'status' => true,
            'message' => 'Added Successfully',
        ], 200);
    }
    public function ActivateCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
        ]);
        if (auth()->user()->role_id == 2) {
            $var = course::where('id', request()->course_id)->get();
            if (count($var) == 0) {
                return response([
                    'message' => "unfound course"
                ], 200);
            }
            $var = course::find($var[0]['id']);
            $var->Ended = 1;
            $var->save();
            return response([
                'status' => true,
                'message' => 'Course activated Successfully .',
            ], 200);
        }
        return response([
            'message' => 'Sorry , This process is for teachers only .',
        ], 200);
    }
    public function DeleteCourse(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
        ]);
        $var = course::where('id', request()->course_id)->get();
        if (count($var) == 0) {
            return response([
                'message' => "unfound course"
            ], 200);
        }
        if ($var[0]['Ended'] == 0) {
            course::where('id', request()->course_id)->delete();
            return response([
                'status' => true,
                'Results' => "course Deleted ."
            ], 200);
        }
    }
    public function MyBusiness()
    {
        if (auth()->user()->role_id != 2)
            return response([
                'message' => 'Sorry , This process is for Teachers only .',
            ], 200);
        $cor1 = Course::where('user_id', auth()->user()->id)
            ->where('Ended', 0)
            ->join('kinds', 'kinds.id', 'courses.Kind_id')
            ->get([
                'courses.id as course_id', 'courses.name as course_name',
                'kind_id', 'kinds.name as Kind_name', 'disc', 'price', 'classes_num',
                'language', 'img_url'
            ]);
        $cor2 = Course::where('courses.user_id', auth()->user()->id)
            ->where('Ended', 1)
            ->join('kinds', 'kinds.id', 'courses.Kind_id')
            ->join('subjects', 'subjects.id', 'kinds.subject_id')
            ->get([
                'courses.id as course_id', 'courses.name as course_name',
                'kind_id', 'kinds.name as Kind_name', 'disc', 'price', 'classes_num',
                'language', 'img_url', 'subject_id', 'subjects.name as subject_name',
                'visible', 'subscribers', 'likes', 'dislikes', 'rating',
            ]);
        return response([
            'status' => true,
            'Courses in progress' => $cor1,
            'Finished courses' => $cor2
        ], 200);
    }
}
