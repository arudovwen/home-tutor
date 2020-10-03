<?php

namespace App\Http\Controllers;

use App\Announcement;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('admin')->user();
        return Announcement::where('school_id', $user->school_id)->get();
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
        return Announcement::create([
            'school_id'=>$user->school_id,
            'body' => $request->body,
            'status'=>$request->status
        ]);
    }
    public function getAnn()
    { 
        $user = auth('api')->user();
        $tutor = auth('tutor')->user();
        if (!is_null($user)) {
            return Announcement::where('school_id', $user->school_id)->where('status',1)->get();
        }
        if (!is_null($tutor)) {
            return Announcement::where('school_id', $tutor->school_id)->where('status',1)->get();
        }
      
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function show( $id)
    {
        return Announcement::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function edit(Announcement $announcement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $id)
    {
        $find = Announcement::find($id);
        $find->body = $request->body;
        $find->status = $request->status;
        $find->save();
        return $find;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Announcement  $announcement
     * @return \Illuminate\Http\Response
     */
    public function destroy( $id)
    {
        $school = Announcement::find($id);
        $school->delete();
        return response()->json([
            'status' => 'Removed'
        ]);
    }
}
