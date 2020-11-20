<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'post';
    protected $fillable = ['user_id', 'description', 'tag', 'g_lat', 'g_lng'];

    public function scopeLimit30days($query)
    {
        $dt = Carbon::create(date('Y'), date('m'), date('d'), 0);
        return $query->where('created_at', '>=', $dt->subDays(30));
    }

    public function files()
    {
        return $this->hasOne(PostFile::class, 'post_id', 'id');
    }

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
