<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Course extends Model
{
    use SoftDeletes;

    protected $table = 'course';
    protected $fillable = ['stores_id','name_th','name_en','professor','cover','full_cost','discount_cost','num_course','num_hour','num','type_course','description','user_id'];
}
