<?php

namespace App\Http\Controllers;

use App\Courses;
use App\Department;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $admin = auth('admin')->user();
        return Courses::where('school_id', $admin->school_id)->get();
      
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
        $user = auth('admin')->user();
       
        return Courses::create([
            'school_id'=> $user->school_id,
            'department_id'=>$request->department_id,
            'faculty_id'=>$request->faculty_id,
            'name'=>$request->name,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function show(Courses $courses)
    {
        return  Courses::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function edit(Courses $courses)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $find = Courses::find($id);
        $find->name = $request->name;
        $find->department_id = $request->department_id;
        $find->faculty_id = $request->faculty_id;
        $find->save();
        return $find;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Courses  $courses
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        Courses::find($id)->delete();
    }
}
