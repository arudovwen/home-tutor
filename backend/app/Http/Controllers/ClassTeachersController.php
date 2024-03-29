<?php

namespace App\Http\Controllers;

use App\Tutor;
use App\Classes;
use App\ClassTeacher;
use Illuminate\Http\Request;
use App\Http\Resources\ClassTeacherResource;

class ClassTeachersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $school_id = auth('admin')->user()->school_id;
        $all = ClassTeacher::where('school_id', $school_id)->get();
       

         return  ClassTeacherResource::collection($all);
         
        
     
       
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
     
        $school_id = auth('admin')->user()->school_id;
       
        $find = ClassTeacher::where('tutor_id', $request->tutor['id'])->first();
      
        if (is_null($find)) {
            return ClassTeacher::create([
            'school_id'=>  $school_id,
            'tutor_id'=> $request->tutor['id'],
            'class_id' => $request->class['id']
        ]);
        } else {
           $find->class_id =$request->class['id'];
           $find->save();
            return $find;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClassTeacher  $classTeacher
     * @return \Illuminate\Http\Response
     */
    public function show(ClassTeacher $classTeacher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClassTeacher  $classTeacher
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassTeacher $classTeacher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClassTeacher  $classTeacher
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $find = ClassTeacher::where('tutor_id', $id)->first();
        $find->school_id =  $school_id;
        $find->tutor_id = $request->tutor['id'];
        $find->class_id = $request->class['id'];
        $find->save();
        return $find;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClassTeacher  $classTeacher
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClassTeacher::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = ClassTeacher::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
