<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ResourceHistory extends Model
{
   protected $table = 'resource_history';
    protected $fillable = ['school_id','user_id','course_id','subject','cover','title','type','index','type','module','progress'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
