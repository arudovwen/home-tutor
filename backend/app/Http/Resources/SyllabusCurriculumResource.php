<?php

namespace App\Http\Resources;

use App\Tutor;
use App\Resource;
use App\Syllabus;
use App\Curriculum;
use Illuminate\Http\Resources\Json\JsonResource;

class SyllabusCurriculumResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       
        $tutor=auth('tutor')->user();
        $student=auth('api')->user();
        $admin=auth('admin')->user();
        if (!is_null($student)) {
            $user=auth('api')->user();
        }
        if (!is_null($tutor)) {
            $user=auth('tutor')->user();
        }
        if (!is_null($admin)) {
            $user=auth('admin')->user();
        }
      
        $arr = [];
        $tutor ;
       
        foreach(json_decode($this->syllabus)->modules as $val){
            $co =  Resource::where('school_id', $user->school_id)->where('subject',$this->subject)->where('module',$val->name)->where('level',$this->myclass)->where('status','approved')->first();
           
        if(!is_null($co)){
            $val->course = $co->content; 
            $val->id = $co->id; 
            $val->excerpt = $co->excerpt; 
            $tutor = $co->tutor_id;  
        }else{
            $val->course = json_encode([]);
        }
        
          array_push($arr, $val);
        }
        return[
             'id'=> $this->id,
             'content'=>$arr,
             'level'=>$this->myclass,           
             'subject'=>$this->subject,          
             'time'=>$this->created_at,
              'syllabus'=>json_decode($this->syllabus),
             'created_at'=>$this->created_at,
             'description'=>json_decode($this->syllabus)->description,
             'cover_image'=>\json_decode($this->syllabus)->cover,
             'tutor'=>Tutor::find($tutor),
             'curriculum'=>Curriculum::find($this->curriculum_id),
        ];
        ;
    }
}
