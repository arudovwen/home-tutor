<?php

namespace App\Http\Controllers;

use App\Classes;
use App\Subject;
use App\CourseTutor;
use App\ClassSubject;
use App\CourseStudent;
use Illuminate\Http\Request;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function regSubject(){
       return Subject::all();
    }
    public function index()
    {
        $school_id = auth('admin')->user()->school_id;
        return Subject::where('school_id',$school_id)->get();
    }
    
    public function getAllSubjectsForStudent()
    {
        $school_id = auth('api')->user()->school_id;
        return Subject::where('school_id',$school_id)->get();
    }
    public function tutorGetAllSubjects()
    {
        $school_id = auth('tutor')->user()->school_id;
        return Subject::where('school_id',$school_id)->get();
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
      
       
        return Subject::create([
            'school_id'=> $school_id,
            'name'=> strtolower($request->name),
            'code' => $request->code,
            'credit' => $request->credit,
            'session' => $request->session,
            'line' => $request->line,
            'level' => $request->category,
            'courses_id' => $request->course
        ]);
    }
    public function getsub(){
        $user = auth('api')->user();
       

      if($user->course_level == 'secondary'){
        
        $sub = Subject::where('school_id', $user->school_id)->whereIn('line',[$user->department,'general'] )->get();
        return $sub;
      }
      if($user->course_level == 'tertiary'){
        $sub = CourseStudent::where('school_id', $user->school_id)->where('student_id',$user->id )->get();
        return $sub[0]->courses;
      }
       $sub = Classes::where('school_id', $user->school_id)->where('class_name', $user->student_level)->first();
        $v = ClassSubject::where('classes_id', $sub->id)->first();
        $arr = [];
        foreach( \json_decode($v->subjects) as $v){
         
            array_push($arr, ['name'=>$v]);
        }
        return $arr;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        return Subject::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function edit(Subject $subject)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $s = Subject::find($id);
        $s->name = $request->name;
        $s->code = $request->code;
        $s->credit = $request->credit;
        $s->session = $request->session;
        $s->line = $request->line;
        $s->courses_id = $request->courses_id;
        $s->save();
        return $s;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subject  $subject
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        Subject::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
        
        foreach ($request->data as $id) {
            $find = Subject::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
