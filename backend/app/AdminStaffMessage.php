<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminStaffMessage extends Model
{
    protected $fillable = ['school_id','tutor_id','admin_id','sender_id','receiver_id','message','attachment'];

    public function tutor(){
        return $this->belongsTo(Tutor::class);
    }
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
}
