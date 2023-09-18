<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\AdminController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// 33 api
Route::post("register", [UserController::class, "register"]);
Route::post("login", [UserController::class, "login"]);
Route::get("ShowTeachers", [UserController::class, "ShowTeachers"]);
Route::post("search", [UserController::class, "search"]);
Route::post("showCourse", [UserController::class, "showCourse"]);
Route::post("AddSuggestion", [UserController::class, "AddSuggestion"]);
Route::get("home", [UserController::class, "home"]);
Route::post("finishCourse", [UserController::class, "finishCourse"]);

Route::group(["middleware" => ["auth:api"]], function () {

    Route::get("profile", [UserController::class, "profile"]);
    Route::post("AddComment", [StudentController::class, "AddComment"]);
    Route::post("ActivateCourse", [TeacherController::class, "ActivateCourse"]);
    Route::post("AddRating", [StudentController::class, "AddRating"]);
    Route::post("like", [StudentController::class, "like"]);
    Route::post("dislike", [StudentController::class, "dislike"]);
    Route::post("logout", [UserController::class, "logout"]);
    Route::post("DeleteAccount", [UserController::class, "DeleteAccount"]);
    Route::post("DeleteCourse", [TeacherController::class, "DeleteCourse"]);
    Route::post("AddCourse", [TeacherController::class, "AddCourse"]);
    Route::post("AddClasss", [TeacherController::class, "AddClasss"]);
    Route::post("AddClasssData", [TeacherController::class, "AddClasssData"]);
    Route::get("MyBusiness", [TeacherController::class, "MyBusiness"]);
    Route::post("BuyCourse", [UserController::class, "BuyCourse"]);
    Route::get("notifications", [AdminController::class, "notifications"]);
    Route::post("AddObjection", [UserController::class, "AddObjection"]);
    Route::post("Hide_course", [AdminController::class, "Hide_course"]);
    Route::post("Un_Hide_course", [AdminController::class, "Un_Hide_course"]);
    Route::post("Register_Admin_Account", [AdminController::class, "Register_Admin_Account"]);
    Route::post("AddKind", [AdminController::class, "AddKind"]);
    Route::post("AddSubject", [AdminController::class, "AddSubject"]);
    Route::post("ShowKinds", [AdminController::class, "ShowKinds"]);
    Route::post("ShowClass", [StudentController::class, "ShowClass"]);
    Route::post("SendAnswers", [StudentController::class, "SendAnswers"]);
    Route::post("AddNotification", [StudentController::class, "AddNotification"]);
});
