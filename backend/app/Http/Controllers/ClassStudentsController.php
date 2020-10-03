<?php

namespace App\Http\Controllers;

use App\User;
use App\ClassStudent;
use Illuminate\Http\Request;

class ClassStudentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_id = auth('admin')->user()->school_id;
        return Classes::where('school_id', $school_id)->get();
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
     
        // $users = User::where('school_id',$school_id)->where('student_level', $request->level)->get();
        foreach ($request->data as $val) {
            $user = User::where('school_id',$school_id)->where('name', $val)->first();
            $user->sub_class = $request->subclass;
            $user->save();
        }
      $find = ClassStudent::where('school_id',$school_id)->where('my_class', $request->level)->first();
      if (is_null($find)) {
        ClassStudent::create([
            'school_id'=>  $school_id,
            'my_class'=>   $request->level,
            'students' => json_encode($request->data)
        ]);
      }else{
          $find->students =  json_encode($request->data);
          $find->save();
      }
          
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ClassStudent  $classStudent
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  ClassStudent::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ClassStudent  $classStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassStudent $classStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ClassStudent  $classStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassStudent $classStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ClassStudent  $classStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClassStudent::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = ClassStudent::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
