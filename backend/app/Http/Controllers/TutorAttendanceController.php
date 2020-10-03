<?php

namespace App\Http\Controllers;

use App\TutorAttendance;
use Illuminate\Http\Request;

class TutorAttendanceController extends Controller
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
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth('tutor')->user();
        return TutorAttendance::create([
            'school_id'=>$user->school_id,
            'tutor_id'=>$user->id,
            'date' =>$request->date,
            'time' => $request->time,
            'record'=>$request->record
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TutorAttendance  $tutorAttendance
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $user = auth('tutor')->user();
         return TutorAttendance::find($id);
    }
    public function getAtt()
    {
         $user = auth('tutor')->user();
         return TutorAttendance::where('tutor_id', $user->id)->get();
    }

    public function getAttForAdmin()
    {
         $user = auth('admin')->user();
         return TutorAttendance::with('tutor')->where('school_id', $user->school_id)->get();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TutorAttendance  $tutorAttendance
     * @return \Illuminate\Http\Response
     */
    public function edit(TutorAttendance $tutorAttendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TutorAttendance  $tutorAttendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TutorAttendance $tutorAttendance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TutorAttendance  $tutorAttendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(TutorAttendance $tutorAttendance)
    {
        //
    }
}
