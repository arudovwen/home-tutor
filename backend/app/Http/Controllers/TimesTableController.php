<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\TimesTable;
use Illuminate\Http\Request;

class TimesTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_id = auth('admin')->user()->school_id;
        return TimesTable::where('school_id', $school_id)->get();
    }
    public function indexTutor()
    {
        $school_id = auth('tutor')->user()->school_id;
        return TimesTable::where('school_id', $school_id)->get();
    }
    
    public function getTimesTablePerClass($id)
    {
        $user = auth('api')->user();
        return TimesTable::where('school_id', $user->school_id)->where('myclass', $user->sub_class)->get();
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
        $times = TimesTable::where('school_id',$school_id)->where('myclass', $request->myclass)->first();
     
       
        if (is_null($times)) {
            return TimesTable::create([
            'school_id'=>  $school_id,
            'myclass'=> $request->myclass,
            'my_class'=> $request->my_class,
            'category'=>$request->category,
            'table' => json_encode($request->table)
        ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TimesTable  $timesTable
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return TimesTable::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TimesTable  $timesTable
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TimesTable  $timesTable
     * @return \Illuminate\Http\Response
     */

    public function getCurrentTimesTable($class)
    {
        $user = auth('api')->user();
        $time = TimesTable::where('myclass', $class)->where('school_id', $user->school_id)->firstOrFail();
 
        $today = strtolower(Carbon::now()->englishDayOfWeek);
        $arr = [];
       
        foreach (json_decode($time->table) as $value) {
            if (\strtolower($value->day) == $today) {
                array_push($arr, $value);
            }
        }
        return  $arr;
    }

    public function getCurrentClass()
    {
        $user = auth('tutor')->user();
      
        $times = TimesTable::where('school_id', $user->school_id)->get();
        $today = strtolower(Carbon::now()->englishDayOfWeek);
        $arr = [];
        $ar =[];
        foreach ($times as $time) {
            foreach (json_decode($time->table) as $value) {
                if (strtolower($value->day) == $today) {
                    foreach ($value->courses as $r) {
                        $r->day = $value->day;
                        $r->level = $time->myclass;
                        array_push($arr, $r);
                    }
                }
            }
        }
        foreach ($arr as $value) {
            if ($value->tutor == $user->name) {
                array_push($ar, $value);
            }
        }
     
        return  $ar;
    }
    public function getAllDayClass()
    {
        $user = auth('admin')->user();
        $times = TimesTable::where('school_id', $user->school_id)->get();
        $today = strtolower(Carbon::now()->englishDayOfWeek);
        $arr = [];
        $ar =[];
        foreach ($times as $time) {
          
            foreach (json_decode($time->table) as $value) {
                if (strtolower($value->day) == $today) {
                    foreach ($value->courses as $r) {
                        $r->day = $value->day;
                        $r->level = $time->myclass;
                      
                        array_push($arr, $r);
                    }
                }
            }
        }
    
       
        return  $arr;
    }
    public function getAllClass()
    {
        $user = auth('tutor')->user();
        $times = TimesTable::where('school_id', $user->school_id)->get();
        $today = strtolower(Carbon::now()->englishDayOfWeek);
        $arr = [];
        $ar =[];
        foreach ($times as $time) {
            foreach (json_decode($time->table) as $value) {
                foreach ($value->courses as $r) {
                    $r->day = $value->day;
                    $r->level = $time->myclass;
                  
                    array_push($arr, $r);
                }
            }
        }
    
        foreach ($arr as $v) {
            if ($v->tutor == $user->name) {
                array_push($ar, $v);
            }
        }
       
        return  $ar;
    }
    public function update(Request $request, $id)
    {
        $s = TimesTable::find($id);
        $s->myclass = $request->myclass;
        $s->my_class = $request->my_class;
        $s->table =  json_encode($request->table);
        $s->category= $request->category;
        $s->save();
        return $s;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TimesTable  $timesTable
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        TimesTable::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
        foreach ($request as $id) {
            $find = TimesTable::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
