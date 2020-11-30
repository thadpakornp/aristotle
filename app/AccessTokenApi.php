<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccessTokenApi extends Model
{
    protected $table = 'access_userapi';

    protected $fillable = ['user_id','token_device','uuid_device'];
}
