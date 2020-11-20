<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseFile extends Model
{
    protected $table = 'course_file';
    protected $fillable = ['course_id','name'];
}
