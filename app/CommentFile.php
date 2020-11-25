<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommentFile extends Model
{
    use SoftDeletes;

    protected $table = 'post_comment_file';

    protected $fillable = ['comment_id', 'name', 'old_name'];
}
