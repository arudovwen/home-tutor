<?php

namespace App;

use App\User;
use App\Tutor;
use App\Courses;
use App\Faculty;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{

    
protected $fillable = ['name','school_id','faculty_id','head'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function tutor()
    {
        return $this->hasMany(Tutor::class);
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
    public function courses()
    {
        return $this->hasMany(Courses::class);
    }
}
