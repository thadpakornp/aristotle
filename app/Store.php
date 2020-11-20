<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Store extends Model
{
    use SoftDeletes;

    protected $table = 'stores';
    protected $fillable = ['name','description','address','district','amphur','province','zipcode','phone','email','line','g_lat','g_lng','user_id','status'];

    public function getPhoneAttribute($phone)
    {
        if($phone == null){
            return null;
        }
        try {
            return Crypt::decryptString($phone);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getEmailAttribute($email)
    {
        if($email == null){
            return null;
        }
        try {
            return Crypt::decryptString($email);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getLineAttribute($line)
    {
        if($line == null){
            return null;
        }
        try {
            return Crypt::decryptString($line);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
