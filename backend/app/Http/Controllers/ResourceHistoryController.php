<?php

namespace App\Http\Controllers;

use App\ResourceHistory;
use Illuminate\Http\Request;

class ResourceHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user= auth('api')->user();
        return ResourceHistory::with('user')->where('school_id',$user->school_id)->where('user_id', $user->id)->latest()->get();
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
    public function store(ResourceHistory $resourceHistory,Request $request)
    {
     
      $user= auth('api')->user();
     $check  = ResourceHistory::where('user_id', $user->id)->where('course_id', $request->id)->latest()->first();
     if (is_null($check)) {
       return  $resource = $user->resourceHistory()->create([
         'school_id'=> $user->school_id,
         'course_id'=> $request->id,
         'subject'=> $request->subject,
         'cover'=> $request->cover,
         'title'=> $request->title,
         'type'=> $request->type,
         'index'=> $request->index,
         'module'=> $request->module,
         'progress'=> $request->progress,

        ]);
     }else{
         $check->module= $request->module;
         $check->progress= $request->progress;
         $check->type= $request->type;
         $check->subject= $request->subject;
         $check->cover= $request->cover;
         $check->title= $request->title;
         $check->course_id= $request->id;
         $check->index= $request->index;
         $check->progress= $request->progress;
         $check->save();
         return $check;
     }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ResourceHistory  $resourceHistory
     * @return \Illuminate\Http\Response
     */
    public function show(ResourceHistory $resourceHistory)
    {
        
    }
    public function getlastread(){
       
        $user= auth('api')->user();
        return ResourceHistory::with('user')->where('school_id',$user->school_id)->where('user_id', $user->id)->latest()->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ResourceHistory  $resourceHistory
     * @return \Illuminate\Http\Response
     */
    public function edit(ResourceHistory $resourceHistory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ResourceHistory  $resourceHistory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResourceHistory $resourceHistory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ResourceHistory  $resourceHistory
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResourceHistory $resourceHistory)
    {
        //
    }
}
