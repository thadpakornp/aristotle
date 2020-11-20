<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostFile extends Model
{
    use SoftDeletes;

    protected $table = 'post_file';
    protected $fillable = ['name','old_name','type_file'];
}
