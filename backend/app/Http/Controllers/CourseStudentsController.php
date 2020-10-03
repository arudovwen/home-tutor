<?php

namespace App\Http\Controllers;

use App\CourseStudent;
use Illuminate\Http\Request;

class CourseStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_id = auth('tutor')->user()->school_id;
        return CourseStudent::where('school_id', $school_id)->get();
    }
    public function indexAdmin()
    {
        $school_id = auth('admin')->user()->school_id;
        return CourseStudent::where('school_id', $school_id)->get();
    }
    public function indexUser()
    {
        $school_id = auth('api')->user()->school_id;
        return CourseStudent::where('school_id', $school_id)->get();
    }


    public function getCourses()
    {
        $user = auth('api')->user();
        return CourseStudent::where('school_id', $user->school_id)->where('student_id',$user->id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
       
      
        $find = CourseStudent::where('student_id',  $request->student['id'])->where('level',  $request->student['level'])->first();
        $school_id = auth('admin')->user()->school_id;
        if (is_null($find)) {
            return CourseStudent::create([
            'school_id'=>  $school_id,
            'student_id'=>  $request->student['id'],
            'level'=>  $request->student['level'],
            'courses' => json_encode($request->choosen_course)
        ]);
        } else {
          
          
            $find->courses = json_encode($request->choosen_course);
            $find->save();
            return $find;
        }
    }

    public function registerCourses(Request $request)
    { 
        $user = auth('api')->user();
      
        $find = CourseStudent::where('student_id',  $user->id)->where('level',  $user->student_level)->first();
     
        if (is_null($find)) {
            return CourseStudent::create([
            'school_id'=>  $user->school_id,
            'student_id'=>  $user->id,
            'level'=>  $user->student_level,
            'courses' => json_encode($request->choosen_course)
        ]);
        } else {
          
          
            $find->courses = json_encode($request->choosen_course);
            $find->save();
            return $find;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CourseStudent  $courseStudent
     * @return \Illuminate\Http\Response
     */
    public function show(CourseStudent $courseStudent)
    {
        return CourseStudent::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CourseStudent  $courseStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseStudent $courseStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseStudent  $courseStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $find = CourseStudent::$id($id);
        $find->courses = json_encode($request->choosen_course);
        $find->save();
        return $find;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseStudent  $courseStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CourseStudent::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
      
        foreach ($request->data as $id) {
            $find = CourseStudent::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
