<?php

namespace App\Http\Controllers;

use App\User;
use App\Assessment;
use App\Notification;
use Illuminate\Http\Request;
use App\Events\MyNotification;

class AssessmentController extends Controller
{
    public function index()
    {
        $school_id = auth('tutor')->user()->school_id;
        $tutor_id = auth('tutor')->user()->id;
        return Assessment::where('school_id', $school_id)->where('tutor_id', $tutor_id)->get();
    }
    public function allass()
    {
        $school_id = auth('tutor')->user()->school_id;
        $tutor_id = auth('tutor')->user()->id;
        return Assessment::where('school_id', $school_id)->get();
    }
    public function show($id)
    {
        $school_id = auth('tutor')->user()->school_id;
        $tutor_id = auth('tutor')->user()->id;
        return Assessment::where('type', $id)->where('school_id', $school_id)->where('tutor_id', $tutor_id)->get();
    }

    public function showNow($id){
        return Assessment::find($id);
    }
    public function getDraft($id)
    {
        $school_id = auth('tutor')->user()->school_id;
        $tutor_id = auth('tutor')->user()->id;
        return Assessment::where('type', $id)->where('school_id', $school_id)->where('tutor_id', $tutor_id)->where('status', '=', 'draft')->get();
    }
    public function getAdminAssessment($id)
    {
        return Assessment::find($id);
    }
    public function getSingleAssessment($id)
    {
        return Assessment::find($id);
    }
    public function getAssessments($id)
    {
        $user = auth('api')->user();
       
        return Assessment::where('school_id', $user->school_id)->where('level', $user->student_level)->where('status', 'approved')->where('publish_status', 1)->get();
    }
    public function getAssessmentsType($id)
    {
        $user = auth('api')->user();
       
        return Assessment::where('type', $id)->where('school_id', $user->school_id)->where('level', $user->student_level)->where('status', 'approved')->where('publish_status', 1)->get();
    }
    public function store(Request $request)
    {
      
        $user = auth('tutor')->user();
    
        $find = Assessment::where('type', $request->type)->where('school_id', $user->school_id)->where('subject',$request->subject)->where('title',$request->title)->where('tutor_id',$user->id)->where('session', $request->session)->where('level',$request->level)->first();
      if(is_null($find)){
        return Assessment::create([
            'school_id'=>$user->school_id,
            'tutor_id'=>$user->id,
            'title'=>$request->title,
            'session'=>$request->session,
            'subject'=> $request->subject,
            'type'=>$request->type,
            'duration'=>$request->duration,
            'status'=>'pending',
            'level'=>$request->level,
            'total_score'=>$request->total_score,
            'start'=>$request->start,
            'end'=>$request->end,
            'description'=>$request->description,
            'questions'=>$request->questions,
            'feedback'=>$request->feedback,
            'deadline'=>$request->deadline,
            'option'=>\json_encode($request->option),
            'assessment'=>\json_encode($request->assessment),
            'publish_status'=>false
        ]);
      }else{
        $find->title=$request->title;
        $find->session=$request->session;
        $find->subject= $request->subject;
        $find->type=$request->type;
        $find->duration=$request->duration;
        $find->status='pending';
        $find->level=$request->level;
        $find->total_score=$request->total_score;
        $find->start=$request->start;
        $find->end=$request->end;
        $find->description=$request->description;
        $find->questions=$request->questions;
        $find->feedback=$request->feedback;
        $find->deadline=$request->deadline;
        $find->option=\json_encode($request->option);
        $find->assessment=\json_encode($request->assessment);
        $find->publish_status=false;
        $find->save();
        return $find;
      }
       
    }

    public function saveDraft(Request $request)
    {
        $school_id = auth('tutor')->user()->school_id;
        $tutor_id = auth('tutor')->user()->id;
        $user = auth('tutor')->user();
      

        $find = Assessment::where('type', $request->type)->where('school_id', $user->school_id)->where('subject',$request->subject)->where('title',$request->title)->where('tutor_id',$user->id)->where('session', $request->session)->where('level',$request->level)->first();
     if(is_null($find)){
        return Assessment::create([
            'school_id'=>$school_id,
            'tutor_id'=>$tutor_id,
            'title'=>$request->title,
            'session'=>$request->session,
            'subject'=> $request->subject,
            'type'=>$request->type,
            'duration'=>$request->duration,
            'status'=>'draft',
            'level'=>$request->level,
            'total_score'=>$request->total_score,
            'start'=>$request->start,
            'end'=>$request->end,
            'option'=>\json_encode($request->option),
            'assessment'=>\json_encode($request->assessment),
            'publish_status'=>false
        ]);
     }else{
        $find->title=$request->title;
        $find->session=$request->session;
        $find->subject= $request->subject;
        $find->type=$request->type;
        $find->duration=$request->duration;
        $find->status='draft';
        $find->level=$request->level;
        $find->total_score=$request->total_score;
        $find->start=$request->start;
        $find->end=$request->end;
        $find->description=$request->description;
        $find->questions=$request->questions;
        $find->feedback=$request->feedback;
        $find->deadline=$request->deadline;
        $find->option=\json_encode($request->option);
        $find->assessment=\json_encode($request->assessment);
        $find->publish_status=false;
        $find->save();
        return $find;
     }
       
    }

    public function getAdminAssessments()
    {
        $admin = auth('admin')->user();
       
        return Assessment::where('school_id', $admin->school_id)->where('status', '!=', 'draft')->get();
    }
    public function verifyAssessment(Request $request, $id)
    {
        $admin = auth('admin')->user();
        $find =  Assessment::find($id);
       
      
        $find->status = $request->status;
        $find->save();
        
        return response()->json([
        'status' => 'updated'
    ]);
    }
    public function publish(Request $request, $id)
    {
        $tutor = auth('tutor')->user();

       
        $find =  Assessment::find($id);
       
  
        $find->publish_status = $request->stat;
        $find->save();

       if ($request->stat) {
        $users = User::where('student_level', $find->level)->get();
        foreach ($users as $user) {
            Notification::create([
            'school_id'=>$tutor->school_id,
            'receiver_id'=>$user->id,
            'message'=>'New '.ucfirst($find->subject).' '.ucfirst($find->type).'  is available',
            'status'=> false,
            'sender_id'=> $tutor->id ,
            'role' => 'student',
            'type'=>'Assessment',
            'resource_id'=>$find->id,
        ]);
            broadcast(new MyNotification($user, $find));
        }
       
       }
        
        return response()->json([
        'status' => 'updated'
    ]);
    }

    public function update (Request $request, $id){

       
        $val = Assessment::find($id);
        $val->option = \json_encode($request->option);
        $val->assessment =  \json_encode($request->assessment);

        $val->save();
        return $val;
    }
    public function destroy($id)
    {
        $admin = Assessment::find($id);
        $admin->delete();
        return response()->json([
            'status' => 'Removed'
        ]);
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = Assessment::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
