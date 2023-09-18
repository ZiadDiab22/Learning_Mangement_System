<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\course;
use App\Models\User;
use App\Models\objection;
use App\Models\notification;
use App\Models\suggestion;
use App\Models\kind;
use App\Models\subject;

class AdminController extends Controller
{
    public function notifications()
    {
        if (auth()->user()->role_id != 1)
            return response([
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);
        $obj = notification::join('users', 'users.id', 'notifications.user_id')
            ->leftjoin('classses', 'classses.course_id', 'notifications.course_id')
            ->where('index', 1)
            ->get(['users.name AS user name', 'notifications.name AS notification', 'classses.id']);
        $sug = suggestion::get(['name']);
        return response([
            'status' => true,
            'objections' => $obj,
            'suggestions' => $sug
        ], 200);
    }
    public function Hide_course(Request $request)
    {
        if (auth()->user()->role_id != 1)
            return response([
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);
        $request->validate([
            'course_id' => 'required',
        ]);
        if (!course::where('id', $request->course_id)->exists())
            return response([
                'message' => "unfound course"
            ], 200);
        $var = course::find($request->course_id);
        $var->visible = 0;
        $var->save();
        return response([
            'status' => true,
            'message' => 'course hide successfully',
        ], 200);
    }
    public function Un_Hide_course(Request $request)
    {
        if (auth()->user()->role_id != 1)
            return response([
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);
        $request->validate([
            'course_id' => 'required',
        ]);
        if (!course::where('id', $request->course_id)->exists())
            return response([
                'message' => "unfound course"
            ], 200);
        $var = course::find($request->course_id);
        $var->visible = 1;
        $var->save();
        return response([
            'status' => true,
            'message' => 'course unhide successfully',
        ], 200);
    }
    public function Register_Admin_Account(Request $request)
    {
        if (auth()->user()->role_id != 1)
            return response([
                'status' => false,
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);
        $request->validate([
            'name' => 'required',
            'email' => 'email|required|unique:users',
            'password' => 'required',
            'phone_no' => 'required',
        ]);

        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role_id = 1;
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
    public function AddSubject(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);
        if (auth()->user()->role_id != 1)
            return response([
                'status' => false,
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);

        $subject = new subject();
        $subject->name = request()->name;
        $subject->save();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
        ], 200);
    }
    public function AddKind(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'subject_id' => 'required'
        ]);
        if (auth()->user()->role_id != 1)
            return response([
                'status' => false,
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);

        $kind = new kind();
        $kind->name = request()->name;
        $kind->subject_id = request()->subject_id;
        $kind->save();

        return response([
            'status' => true,
            'message' => 'Added Successfully',
        ], 200);
    }
    public function ShowKinds(Request $request)
    {
        $request->validate([
            'subject_id' => 'required',
        ]);
        if (auth()->user()->role_id != 1)
            return response([
                'status' => false,
                'message' => 'Sorry , This process is for Admins only .',
            ], 200);

        $var = kind::where('subject_id', $request->subject_id)
            ->get(['name as kind_name']);

        return response([
            'status' => true,
            'Result' => $var,
        ], 200);
    }
}
