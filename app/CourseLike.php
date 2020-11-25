<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CourseLike extends Model
{
    protected $table = 'course_like';

    protected $fillable = ['user_id','course_id'];
}
