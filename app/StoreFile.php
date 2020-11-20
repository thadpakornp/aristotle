<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreFile extends Model
{
    protected $table = 'stores_image';
    protected $fillable = ['stores_id','name'];
}
