<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreFollow extends Model
{
    protected $table = 'store_follow';

    protected $fillable = ['user_id','store_id'];
}
