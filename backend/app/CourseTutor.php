<?php

namespace App;

use App\Tutor;
use Illuminate\Database\Eloquent\Model;

class CourseTutor extends Model
{
    protected $table = 'courses_tutor';
    protected $fillable = ['tutor_id','school_id','courses'];

    public function tutor(){
        return $this->belongsTo(Tutor::class);
    }
}
