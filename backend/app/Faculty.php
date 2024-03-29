<?php

namespace App;

use App\User;
use App\Tutor;
use App\Department;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{


protected $fillable = ['name','school_id','head'];

    public function user()
    {
        return $this->hasMany(User::class);
    }
    public function tutor()
    {
        return $this->hasMany(Tutor::class);
    }
    public function departments(){
        return $this->hasMany(Department::class);
    }
}
