<?php

namespace App\Http\Controllers;

use App\Tutor;
use App\TutorStudent;
use Illuminate\Http\Request;
use App\Events\TutorStudentMessage;

class TutorStudentController extends Controller
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

    public function sendTutorMessage(Request $request)
    {

     
        $tutor = auth('tutor')->user();
        $message = TutorStudent::create([
            'school_id'=> $tutor->school_id,
            'user_id'=>$tutor->id,
            'receiver_id'=>$request->user_id,
        'message' => $request->input('message'),
        'attachment'=>$request->attachment
      ]);
    
        broadcast(new TutorStudentMessage($tutor, $message))->toOthers();
    
        return ['status' => 'Message Sent!'];
    }
    public function sendStudentMessage(Request $request)
    {
        $user = auth('api')->user();
        $message = $user->studenttutormessages()->create([
            'school_id'=> $user->school_id,
            'user_id'=>$user->id,
            'receiver_id'=>$request->tutor_id,
        'message' => $request->input('message'),
        'attachment'=>$request->attachment
      ]);
    
        broadcast(new TutorStudentMessage($user, $message))->toOthers();
    
        return ['status' => 'Message Sent!'];
    }
    public function getTutors()
    {
        $user = auth('api')->user();
        return Tutor::where('school_id', $user->school_id)->get();
    }


    public function fetchMessages()
    {
        return TutorStudent::with('tutor')->with('user')->get();
    }

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TutorStudent  $tutorStudent
     * @return \Illuminate\Http\Response
     */
    public function show(TutorStudent $tutorStudent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TutorStudent  $tutorStudent
     * @return \Illuminate\Http\Response
     */
    public function edit(TutorStudent $tutorStudent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TutorStudent  $tutorStudent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TutorStudent $tutorStudent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TutorStudent  $tutorStudent
     * @return \Illuminate\Http\Response
     */
    public function destroy(TutorStudent $tutorStudent)
    {
        //
    }
}
