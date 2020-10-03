<?php

namespace App;

use App\Classes;
use Illuminate\Database\Eloquent\Model;

class ClassSubject extends Model
{
    protected $fillable = ['school_id','level','subjects','classes_id'];

    public function classes(){
        return $this->belongsTo(Classes::class);
    }
}
