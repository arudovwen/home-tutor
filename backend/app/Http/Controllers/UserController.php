<?php

namespace App\Http\Controllers;

use App\User;
use stdClass;
use App\Tutor;
use App\School;
use Carbon\Carbon;
use App\TimesTable;
use App\PasswordGenerator;
use Illuminate\Http\Request;
use App\Notifications\NewStudent;
use App\Notifications\PasswordReset;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth('api')->user();
        $user->abbreviation = School::where('id', $user->school_id)->value('abbreviation');
        return $user;
    }
    public function adminGetStudents()
    {
        $school_id = auth('admin')->user()->school_id;
        return User::where('school_id', $school_id)->get();
    }
    public function tutorGetStudents()
    {
        $school_id = auth('tutor')->user()->school_id;
        return User::where('school_id', $school_id)->get();
    }
 public function resetPassword(Request $request){
   
    if($request->user=='student'){
        $find= User::where(\strtolower('email'),\strtolower($request->email))->first();
    }else{
        $find= Tutor::where(\strtolower('email'),\strtolower($request->email))->first();
    }
     if (is_null($find)) {
       return response()->json([
           'status'=>'failed'
       ]);
     }else{
        $password = new PasswordGenerator();
         $mypass = $password->random_strings(8);
         $find->password = Hash::make($mypass);      
         $find->save();
        $find->pass = $mypass;
        $school = School::find($find->school_id);
        $find->notify(new PasswordReset($school,$find));
        return response()->json([
            'status'=>'success'
        ]);
     }
 }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function newLecture()
    {
        $users = User::all();

       foreach($users as $user){
        $time = TimesTable::where('myclass', $user->sub_class)->where('school_id', $user->school_id)->firstOrFail();
        $today = strtolower(Carbon::now()->englishDayOfWeek);
        $arr = [];
        foreach (json_decode($time->table) as $value) {
            if (strtolower($value->day)== $today) {
                foreach ($value->courses as $r) {
                    $r->day = $value->day;
                    $r->level = $time->myclass;
                    array_push($arr, $r);
                }
            }
        }
        $now = strtotime(Carbon::now('Africa/Lagos')->format('H:i'));
       
        $data= [];
       
        foreach ($arr as $value) {
            $noww = strtotime(Carbon::parse('9:01')->format('H:i'));
            
            $time = strtotime(Carbon::parse($value->start)->format('H:i'));
            if ($time=== $noww) {
                $data = [
                        'subject' =>  $value->subject,
                        'time' => $value->start,
                        'tutor' => $value->tutor,
                ];
            } else {
                return 'nah';
            }
        }
        return $data;
       }
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    public function show()
    {
        $id = auth('api')->user()->id;
        return User::find($id);
    }
    public function adminGetUser($id)
    {
        return User::find($id);
    }
    
    public function getUser()
    {
        $id = auth('api')->user()->id;
        return User::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::where('id', $id)->first();
        $user->name = $request['name'];
        $user->email = $request->email;
        $user->phone = $request['phone'];
        // $user->password = $request['password'];
        $user->gender = $request['gender'];
        $user->address = $request['address'];
        $user->course_level= $request['course_level'];
        $user->dob = $request['dob'];
        $user->state = $request['state'];
        $user->profile = $request['profile'];
        $user->lga = $request['lga'];
        $user->guardian= $request['guardian'];
        $user->guardian_phone= $request['guardian_phone'];
        $user->next_of_kin = $request['next_of_kin'];
        $user->next_of_kin_phone = $request['next_of_kin_phone'];
        $user->student_level = $request['student_level'];
        $user->study_course = $request['study_course'];
        $user->department = $request['department'];
        $user->faculty = $request['faculty'];
        $user->mat_no = $request['mat_no'];
        $user->save();
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $admin = User::find($id);
        $admin->delete();
        return response()->json([
            'status' => 'Removed'
        ]);
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = User::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
