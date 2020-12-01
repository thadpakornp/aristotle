<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use SoftDeletes;

    protected $table = 'notifications';
    protected $fillable = ['user_id_from', 'user_id_to', 'ref_id', 'noti_type', 'status'];
}
