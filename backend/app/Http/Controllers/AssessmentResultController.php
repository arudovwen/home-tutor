<?php

namespace App\Http\Controllers;

use App\User;
use App\GradeBook;
use App\Notification;
use App\AssessmentResult;
use Illuminate\Http\Request;
use App\Events\ResourceAdded;
use App\DraftAssessmentResult;
use App\Events\MyNotification;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\AssessmentResultResource;

class AssessmentResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('tutor')->user();
        return AssessmentResultResource::collection(AssessmentResult::where('school_id', $user->school_id)->where('tutor_id', $user->id)->get());
    }

    public function indexAdmin()
    {
        $user = auth('admin')->user();
        return AssessmentResultResource::collection(AssessmentResult::where('school_id', $user->school_id)->get());
    }

    public function getassresult($id,$type,$title,$level,$subject){

       
        $user = auth('tutor')->user();
       return AssessmentResult::with('user')->where('school_id', $user->school_id)->where('user_id',$id)->where(strtolower('type'),strtolower($type))->where(strtolower('title'),strtolower($title))->where(strtolower('level'),strtolower($level))->where(strtolower('subject'),strtolower($subject))->first();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function allresult()
    {
        $user = auth('tutor')->user();
        return AssessmentResultResource::collection(AssessmentResult::where('school_id', $user->school_id)->get());
      
    }
    public function adminallresult()
    {
        $user = auth('admin')->user();
        return AssessmentResultResource::collection(AssessmentResult::where('school_id', $user->school_id)->get());
      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function checkAssessment(Request $request)
    {
        $user = auth('api')->user();
        $assessment = DraftAssessmentResult::where('school_id', $user->school_id)->where('user_id', $user->id)->where('level', $user->student_level)->where('subject', $request->subject)->where('type', $request->type)->where('title', $request->title)->first();

        if (is_null($assessment)) {
            return response()->json([
                'status'=>'begin'
            ]);
        } else {
            return response()->json([
                'status'=>'ended'
            ]);
        }
    }
    public function ApproveAssessment(Request $request, $id)
    {
        return DB::transaction(function () use ($request,$id) {
            $tutor = auth('tutor')->user();
       
            $find = DraftAssessmentResult::find($id);
            $user = User::find($find->user_id);
            $assessment = AssessmentResult::where('school_id', $user->school_id)->where('user_id', $user->id)->where('level', $user->student_level)->where('subject', $request->subject)->where('type', $request->type)->where('title', $request->title)->first();

           

            $grade = GradeBook::where('school_id', $user->school_id)->where('user_id', $user->id)->where('subject', $request->subject)->where('level', $user->student_level)->first();
       
            if (is_null($assessment)) {
                $result = AssessmentResult::create([
                'user_id'=>$user->id,
                'school_id'=> $user->school_id,
                'tutor_id'=> $tutor->id,
                'level'=>$user->student_level,
                'subject'=>$request->subject,
                'type'=>$request->type,
                'title'=>$request->title,
                'total_score'=>$request->total_score,
                'overall'=>$find->overall,
                'record'=>\json_encode($request->record),
            ]);
            $percentScore = ($request->total_score/$find->overall)*100;
            $currentTotalAssessment = AssessmentResult::where('school_id', $user->school_id)->where('user_id', $user->id)->where('level', $user->student_level)->where('subject', $request->subject)->where('type', $request->type)->get();

                if (is_null($grade)) {
                    $gradeNew = new GradeBook;
                    $gradeNew->user_id=$user->id;
                    $gradeNew->school_id= $user->school_id;
                    $gradeNew->level=$user->student_level;
                    $gradeNew->subject =$request->subject;
                    $gradeNew->title =$request->title;
                    $gradeNew->record=json_encode($request->record);
                    if ($request->type=='quiz') {
                        $gradeNew->quiz =  $percentScore;
                    } elseif ($request->type== 'examination') {
                        $gradeNew->examination =  $percentScore;
                    } elseif ($request->type == 'test') {
                        $gradeNew->test =  $percentScore;
                    } elseif ($request->type == 'assignment') {
                        $gradeNew->assignment =  $percentScore;
                    } elseif ($request->type == 'attendance') {
                        $gradeNew->attendance =  $percentScore;
                    } elseif ($request->type == 'participation') {
                        $gradeNew->participation =  $percentScore;
                    }
                    $gradeNew->total_score = $gradeNew->assignment + $gradeNew->examination + $gradeNew->quiz + $gradeNew->test;
                    $gradeNew->average_quiz =  $gradeNew->average_test =  $gradeNew->average_assignment = 1;
                    $gradeNew->save();
                } else {
                    $grade->user_id=$user->id;
                    $grade->school_id= $user->school_id;
                    $grade->level=$user->student_level;
                    $grade->subject =$request->subject;
                    $grade->title =$request->title;
                    $grade->record=json_encode($request->record);
                    if ($request->type=='quiz') {
                        if (is_null($grade->quiz)) {
                            $grade->quiz =  $percentScore;
                        } else {
                            $grade->quiz = $grade->quiz +  $percentScore;
                        }
                        $grade->average_quiz = count($currentTotalAssessment);
                    } elseif ($request->type== 'examination') {
                        if (is_null($grade->examination)) {
                            $grade->examination =  $percentScore;
                        } else {
                            $grade->examination = $grade->examination +  $percentScore;
                        }
                     
                    } elseif ($request->type == 'test') {
                        if (is_null($grade->test)) {
                            $grade->test =  $percentScore;
                        } else {
                            $grade->test = $grade->test +  $percentScore;
                        }
                        $grade->average_test = count($currentTotalAssessment);
                    }  elseif ($request->type == 'assignment') {
                        if (is_null($grade->assignment)) {
                            $grade->assignment =  $percentScore;
                        } else {
                            $grade->assignment = $grade->assignment +  $percentScore;
                        }
                        $grade->average_assignment = count($currentTotalAssessment);
                    }elseif ($request->type == 'attendance') {
                        if (is_null($grade->attendance)) {
                            $grade->attendance =  $percentScore;
                        } else {
                            $grade->attendance = $grade->attendance +  $percentScore;
                        }
                       
                    }elseif ($request->type == 'participation') {
                        if (is_null($grade->participation)) {
                            $grade->participation =  $percentScore;
                        } else {
                            $grade->participation = $grade->participation +  $percentScore;
                        }
                      
                    }
                    $grade->total_score = $grade->assignment + $grade->examination + $grade->quiz + $grade->test;
                    $grade->save();
                }
                   
                Notification::create([
                        'school_id'=>$tutor->school_id,
                        'receiver_id'=>$user->id,
                        'message'=>ucfirst($request->type).' result is available - '.ucfirst($request->title).' ('.ucfirst($request->subject).')',
                        'status'=> false,
                        'sender_id'=> $tutor->id ,
                        'role' => 'student',
                        'type'=>'Assessment',
                        'resource_id'=>$result->id,
                    ]);
                broadcast(new MyNotification($user, $result));
                $find->status = 'submitted';
                $find->save();
                return $result;
            }
        });
    }
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $assessment = DraftAssessmentResult::where('school_id', $user->school_id)->where('user_id', $user->id)->where('level', $user->student_level)->where('subject', $request->subject)->where('type', $request->type)->where('title', $request->title)->first();


        if (is_null($assessment)) {
            $result = DraftAssessmentResult::create([
                'user_id'=>$user->id,
                'school_id'=> $user->school_id,
                'tutor_id'=> $request->tutor_id,
                'level'=>$user->student_level,
                'subject'=>$request->subject,
                'type'=>$request->type,
                'title'=>$request->title,
                'status'=>$request->pending,
                'overall'=>$request->overall,
                'total_score'=>$request->total_score,
                'record'=>\json_encode($request->record),
                'status'=>'pending'
            ]);
         

            return $result;
        }
    }
    public function getBooks($level)
    {
        $tutor = auth('tutor')->user();
        $level = Classes::where('id', $level)->value('class_name');
        return  AssessmentResultResource::collection(AssessmentResult::where('school_id', $tutor->school_id)->where('level', $level)->get());
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\AssessmentResult  $assessmentResult
     * @return \Illuminate\Http\Response
     */
    public function draftResult(Request $request)
    {
        $tutor = auth('tutor')->user();
        return DraftAssessmentResult::with('user')->where('school_id', $tutor->school_id)->where('tutor_id', $tutor->id)->get();
    }
    public function show($level)
    {
        $tutor = auth('tutor')->user();

        return  AssessmentResultResource::collection(AssessmentResult::where('school_id', $tutor->school_id)->where('level', $level)->get());
    }
    public function getResult()
    {
        $user = auth('api')->user();
        return  AssessmentResult::where('school_id', $user->school_id)->with('user')->where('user_id', $user->id)->latest()->get();
    }

    public function getTutorResult()
    {
        $user = auth('tutor')->user();
        return  AssessmentResult::where('school_id', $user->school_id)->with('user')->get();
    }
    public function getAdminResult()
    {
        $user = auth('admin')->user();
        return  AssessmentResult::where('school_id', $user->school_id)->with('user')->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AssessmentResult  $assessmentResult
     * @return \Illuminate\Http\Response
     */
    public function edit(AssessmentResult $assessmentResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AssessmentResult  $assessmentResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AssessmentResult $assessmentResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AssessmentResult  $assessmentResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(AssessmentResult $assessmentResult)
    {
        //
    }
}
