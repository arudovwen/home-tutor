<?php

namespace App\Http\Controllers;

use App\User;
use App\Classes;
use App\Resource;
use App\Syllabus;
use App\CourseTutor;
use App\Notification;
use Illuminate\Http\Request;
use App\Events\ResourceAdded;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\SyllabusCurriculumResource;

class ResourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $school_id = auth('tutor')->user()->school_id;
        return Resource::where('school_id', $school_id)->get();
    }
    public function indexAdmin()
    {
        $school_id = auth('admin')->user()->school_id;
        return Resource::where('school_id', $school_id)->get();
    }
    public function indexUser()
    {
        $school_id = auth('api')->user()->school_id;
        return Resource::where('school_id', $school_id)->where('status','approved')->get();
    }

    public function getResources()
    {
        $user = auth('api')->user();
        return Resource::where('school_id', $user->school_id)->where('level', $user->student_level)->where('status','approved')->get();
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
        $info =  DB::transaction(function () use ($request) {
            $tutor = auth('tutor')->user();
            $syllabus = Syllabus::where('school_id',$tutor->school_id)->where('subject', $request->subject)->where('myclass',$request->level)->first();
              
        
            $tutor = auth('tutor')->user();
            $resource = Resource::create([
            'school_id' => $tutor->school_id,
            'tutor_id'=> $tutor->id,
            'cover_image'=> $request->cover_image,
            'syllabus_id'=>  $syllabus->id,
            'curriculum_id' => $syllabus->curriculum_id,
            'subject' => strtolower($request->subject),
            'level'  => strtolower($request->level),
            'module'  => $request->module,
            'excerpt'  => $request->excerpt,
            'content'  => json_encode($request->content),
            'worksheet'  => $request->worksheet,
            'worksheet_id'  => $request->worksheet_id,
            'note' => $request->note,
            'status' => 'pending'
        ]);
            $exploded=[];
       
            $users = User::where('student_level', $request->level)->get();
            foreach ($users as $value) {
                Notification::create([
                'school_id'=>$tutor->school_id,
                'receiver_id'=>$value->id,
                'message'=> ucfirst($request->module).'- A new resource has been added to '.ucfirst($request->subject),
                'status'=> false,
                'sender_id'=> $tutor->id ,
                'role' => 'student',
                'type'=>"resource",
                'resource_id'=>$resource->id,
            ]);
                broadcast(new ResourceAdded($users, $resource));
            }
            return $resource;
        });
        return $info;
    }

    /**
     * Display the specified resource.where('school_id',$resource->school_id)->where('myclass', $resource->level)
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

       return  Resource::find($id);
    
      
    }
    public function uploadStat(){
                $tutor = auth('tutor')->user();
                $teacher = CourseTutor::where('tutor_id', $tutor->id)->first();
               $mod = [];
                foreach (\json_decode($teacher->courses) as $value) {
                   $v = Syllabus::where('school_id',$tutor->school_id)->where('subject',strtolower($value))->get();
                   array_push($mod,$v);
                }
                $modules = [];
                foreach ($mod[0] as $v) {
                   array_push($modules,$v->syllabus);
                }
                $module = [];
                foreach ($modules as $m) {
                    foreach (json_decode($m)->modules as $o) {
                        
                        array_push($module,$o->name);
                     }
                  
                 }
               
                $ar = [];
                $resources =  Resource::where('tutor_id',$tutor->id)->get();
                foreach($module as $mo){
               
                 
                }
              
                $data = [
                    'modules'=> $module,
                    'resources' => $resources
                ];
                return $data;
              
    }
    public function adminR($id)
    { 
        $user = auth('admin')->user();

        $resource = Syllabus::find($id);
    
        return  new SyllabusCurriculumResource($resource);
    
      
    }
    public function changeStatus(Request $request,$id){
    $find = Resource::find($id);
    $find->status = $request->status;
    $find->save();
    return $find;
    }
    public function show($id)
    {

        $resource = Syllabus::find($id);
    
        return  new SyllabusCurriculumResource($resource);
    }

    public function getModuleResource($name,$level,$school)
    {
       
      return  $resource = Resource::where('school_id',$school)->where('level',$level)->where('subject',$name)->where('status','approved')->get();

     
    }

    public function getModules($level,$school)
    { 
        $resource = Resource::where('school_id',$school)->where('level',$level)->get();
      
      return  SyllabusCurriculumResource::collection($resource);

       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
   
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $resource = Resource::find($id);
        $resource->subject = $request->subject;
        $resource->level  = $request->level;
        $resource->module  = $request->module;
        $resource->excerpt  = $request->excerpt;
        $resource->content  = json_encode($request->content);
        $resource->worksheet  = $request->worksheet;
        $resource->worksheet_id  = $request->worksheet_id;
        $resource->note = $request->note;
        $resource->status = $request->status;
        $resource->cover_image = $request->cover_image;
        $resource->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Resource::find($id)->delete();
    }
    public function multiDrop(Request $request)
    {
        foreach ($request->data as $id) {
            $find = Resource::find($id);
            $find->delete();
        }
     
        return response()->json([
            'status'=>'Deleted'
        ]);
    }
}
