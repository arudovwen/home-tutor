<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TutorStudent extends Model
{
    protected $table = 'student_tutor_messages';
    protected $fillable =['school_id','user_id','receiver_id','message','attachment'];



    public function tutor(){
        return $this->belongsTo(Tutor::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
