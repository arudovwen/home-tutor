<?php

namespace App;

use App\Department;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    protected $fillable = ['name','school_id','faculty_id','department_id'];
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
