<?php

namespace App;

use App\School;
use App\Classes;
use App\Faculty;
use App\Message;
use App\StaffChat;
use App\Department;
use App\CourseLevel;
use App\CourseTutor;
use App\LiveClasses;
use App\TutorStudent;
use App\EducationLevel;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Tutor extends Authenticatable
{
    use HasApiTokens, Notifiable;



    protected $fillable = [
        'name', 'email', 'password','id_no','phone','gender','level_of_edu','subjects',
        'school',
        'school_id',
        'address', 'lga', 'state', 'bank_name','bank_no','bvn','profile','file_no','grade_level','institution','classess','expertise','resume','certificate','professional_ref','bio'
   
    ];
    public function routeNotificationForMail($notification)
    {
        // Return email address only...
        return $this->email;

        // Return name and email address...
        return [$this->email => $this->name];
    }
    public function school()
    {
        return $this->belongsTo(School::class);
    }
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function educationLevel()
    {
        return $this->belongsTo(EducationLevel::class);
    }
    public function courseLevel()
    {
        return $this->belongsToMany(CourseLevel::class);
    }
    public function classes(){
        return $this->hasOne(Classes::class);
    }
    public function messages(){
        return $this->hasMany(Message::class);
    }
    public function studenttutormessages(){
        return $this->hasMany(TutorStudent::class);
    }
    public function staffMessage(){
        return $this->hasMany(StaffChat::class);
    }
    public function liveClass(){
        return $this->hasMany(LiveClasses::class);
    }
    public function courseTutor(){
        return $this->belongsTo(CourseTutor::class);
    }
}
