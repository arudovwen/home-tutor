<?php

namespace App\Http\Controllers;

use App\Tutor;
use App\School;
use App\Classes;
use App\CourseTutor;
use App\ClassTeacher;
use App\PasswordGenerator;
use App\Events\OnlineTutors;
use Illuminate\Http\Request;
use App\Notifications\NewTutor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TutorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $arr=[];
        $school_id = auth('admin')->user()->school_id;
        $tutors = Tutor::with('courseTutor')->where('school_id', $school_id)->get();
        foreach ($tutors as $tutor) {
            $tutor->abbreviation = School::where('id', $tutor->school_id)->value('abbreviation');
            $course= CourseTutor::where('tutor_id', $tutor->id)->first();
            if (!is_null($course)) {
                $tutor->course = json_decode($course->courses);
            }
            array_push($arr, $tutor);
        }
        return $arr;
    }

  
    public function tutorDetail()
    {
        $tuto = auth('tutor')->user();
        $tutor = Tutor::with('courseTutor')->where('school_id', $tuto->school_id)->where('id', $tuto->id)->first();
        $tutor->abbreviation = School::where('id', $tutor->school_id)->value('abbreviation');
        $c = CourseTutor::where('tutor_id', $tuto->id)->first();
        if (!is_null($c)) {
            $tutor->course = json_decode($c->courses);
        }
        $head = ClassTeacher::where('tutor_id', $tutor->id)->first();
        if (!is_null($head)) {
            $tutor->head = Classes::find($head->class_id);
        }
         broadcast(new OnlineTutors($tutor))->toOthers();
        
        return $tutor;
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
      
        $value = DB::transaction(function () use ($request) {
            $password = new PasswordGenerator();
            $mypass= $password->random_strings(8);
            $tutor = Tutor::create([
            'name' => $request['personal']['name'],
            'email' => $request['personal']['email'],
            'password' => Hash::make($mypass),
            'id_no' => rand(0, 99999),
            'phone' => $request['personal']['phone'],
            'gender' => $request['personal']['gender'],
            'school' => 'E-Learning',
            'school_id' => 1,
            'subjects' => \json_encode($request['experience']['subjects']),
            'address'  => $request['personal']['address'],
              'lga'  => $request['personal']['lga'],
              'state'  => $request['personal']['state'],
              'bank_no'  => $request['profile']['account_no'],
              'bank_name'  => $request['profile']['bank_name'],
              'grade_level' => $request['experience']['grade_level'],
              'institution' => $request['experience']['institution'],
              'classess' => json_encode($request['experience']['classes']),
              'expertise' => json_encode($request['experience']['expertise']),
              'resume' => $request['documents']['resume'],
              'certificate' => $request['documents']['certificates'],
              'professional_ref' => json_encode($request['documents']['profesional_ref']),
              'bio' => $request['profile']['bio'],
              'profile' => $request['profile']['profile_pic'],



        ]);
     
            $tutor->pass = $mypass;
             $tutor->notify(new NewTutor($school, $tutor));
            return $tutor;
        });
        return $value;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Tutor::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Tutor::find($id);
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
        $tutor = Tutor::find($id);
        $tutor['name'] = $request['name'];
        $tutor['email'] = $request['email'];
        $tutor['phone'] = $request['phone'];
        $tutor['gender'] = $request['gender'];
        $tutor['level_of_edu'] = $request['level_of_edu'];
        $tutor['faculty'] = $request['faculty'];
        $tutor['department'] = $request['department'];
        $tutor['course_level']= $request['course_level'];
        $tutor['subjects'] =  $request['subjects'];
        $tutor['address' ] = $request['address'];
        $tutor['dob']  = $request['dob'];
        $tutor['profile']  = $request['profile'];
        $tutor['doe' ] = $request['doe'];
        $tutor['lga']  = $request['lga'];
        $tutor['state']  = $request['state'];
        $tutor['bank_name']  = $request['bank_name'];
        $tutor['bank_no']  = $request['bank_no'];
        $tutor['bvn']  = $request['bvn'];
        $tutor['cgl']  = $request['cgl'];
        $tutor['rank']  = $request['rank'];
        $tutor['file_no']  = $request['file_no'];
        $tutor['area_of_specialization']  = $request['area_of_specialization'];
     
        $tutor->save();
        return $tutor;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tutor = Tutor::find($id);
        $tutor->delete();
        return response()->json([
            'status' => 'Removed'
        ]);
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = Tutor::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
