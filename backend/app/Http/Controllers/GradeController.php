<?php

namespace App\Http\Controllers;

use App\Grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('admin')->user();
        return Grade::where('school_id', $user->school_id)->get();
        
    }
    public function indexTutor()
    {
        $user = auth('tutor')->user();
        return Grade::where('school_id', $user->school_id)->get();
    }
    public function indexStudent()
    {
        $user = auth('api')->user();
        return Grade::where('school_id', $user->school_id)->get();
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
        return Grade::create([
            'school_id'=> $user->school_id,
            'min'=>$request->min,
            'max'=>$request->max,
            'grade'=>$request->name,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $grade = Grade::find($id);
        $grade->min = $request->min;
        $grade->max = $request->max;
        $grade->grade = $request->grade;
        $grade->save();
        return $grade;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Grade  $grade
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        Grade::find($id)->delete();

    }
}
