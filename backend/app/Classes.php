<?php

namespace App;

use App\Arm;
use App\Tutor;
use App\ClassSubject;
use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $table = 'classes';
    protected $fillable = ['school_id','class_name','sub_class','level'];


    public function tutor(){
        return $this->hasOne(Tutor::class);
    }
    public function classsubject(){
        return $this->hasOne(ClassSubject::class);
    }
   
}
