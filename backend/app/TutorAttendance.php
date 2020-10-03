<?php

namespace App;

use App\Tutor;
use Illuminate\Database\Eloquent\Model;

class TutorAttendance extends Model
{
    protected $fillable = ['school_id','tutor_id','date','time','record'];

    public function tutor(){
        return $this->belongsto(Tutor::class);
    }
}