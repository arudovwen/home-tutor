<?php

namespace App\Http\Controllers;

use App\AdminStaffMessage;
use Illuminate\Http\Request;
use App\Events\AdminStaffMessages;

class AdminStaffMessageController extends Controller
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
    public function sendTutorMessage(Request $request)
    {
     
        $user = auth('tutor')->user();
       
        $message = AdminStaffMessage::create([
            'school_id'=> $user->school_id,
            'tutor_id'=> $user->id,
            'admin_id' => $request->admin_id,
            'sender_id' =>'tutor',
            'receiver_id' => 'admin',
            'message'=>$request->message,
            'attachment'=>$request->attachment
        ]);
      
    
         broadcast(new AdminStaffMessages($user, $message))->toOthers();
    
        return ['status' => 'Message Sent!'];
    }
    public function sendAdminMessage(Request $request)
    {
     
        $user = auth('admin')->user();
     
        $message = AdminStaffMessage::create([
            'school_id'=> $user->school_id,
            'admin_id'=> $user->id,
            'tutor_id' => $request->tutor_id,
            'sender_id' =>'admin',
            'receiver_id' => 'tutor',
            'message'=>$request->message,
            'attachment'=>$request->attachment
        ]);
      
    
          broadcast(new AdminStaffMessages($user, $message))->toOthers();
    
        return ['status' => 'Message Sent!'];
    }


    public function fetchMessages()
    {
        return AdminStaffMessage::with('tutor')->with('admin')->get();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdminStaffMessage  $adminStaffMessage
     * @return \Illuminate\Http\Response
     */
    public function show(AdminStaffMessage $adminStaffMessage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdminStaffMessage  $adminStaffMessage
     * @return \Illuminate\Http\Response
     */
    public function edit(AdminStaffMessage $adminStaffMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdminStaffMessage  $adminStaffMessage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdminStaffMessage $adminStaffMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdminStaffMessage  $adminStaffMessage
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdminStaffMessage $adminStaffMessage)
    {
        //
    }
}
