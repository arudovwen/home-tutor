<?php

namespace App\Http\Controllers;

use App\Classes;
use App\Subject;
use App\ClassSubject;
use Illuminate\Http\Request;

class ClassSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user =  auth('admin')->user();
        $arr = [];
        $vals = ClassSubject::where('school_id', $user->school_id)->get();
        foreach ($vals as $value) {
            $value->level = Classes::find($value->classes_id);
         
            array_push($arr, $value);
        }
        return $arr;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user =  auth('admin')->user();
        $find = ClassSubject::where('school_id', $user->school_id)->where('classes_id', $request->id)->first();
        if (is_null($find)) {
            return ClassSubject::create([
            'school_id'=> $user->school_id,
            'classes_id'=> $request->id,
            'subjects' =>  \json_encode($request->subjects),
        ]);
        } else {
            $find->subjects = \json_encode($request->subjects);
            $find->save();
            return $find;
        }
    }
   
    /**
     * Display the specified resource.
     *
     * @param  \App\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function show(ClassSubject $classSubject)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassSubject $classSubject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassSubject $classSubject)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClassSubject  $classSubject
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassSubject $classSubject)
    {
        //
    }
}
