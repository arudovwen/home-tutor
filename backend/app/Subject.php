<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['school_id','name','code','credit','session','courses_id','line','level'];
}
