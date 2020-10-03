<?php

namespace App\Http\Controllers;

use App\CourseTutor;
use Illuminate\Http\Request;

class CourseTutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
       
        $find = CourseTutor::where('tutor_id', $request->id)->first();
        $school_id = auth('admin')->user()->school_id;
        if (is_null($find)) {
            return CourseTutor::create([
            'school_id'=>  $school_id,
            'tutor_id'=> $request->id,
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
     * @param  \App\CourseTutor  $courseTutor
     * @return \Illuminate\Http\Response
     */
    public function show(CourseTutor $courseTutor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CourseTutor  $courseTutor
     * @return \Illuminate\Http\Response
     */
    public function edit(CourseTutor $courseTutor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CourseTutor  $courseTutor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CourseTutor $courseTutor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CourseTutor  $courseTutor
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        CourseTutor::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = CourseTutor::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}