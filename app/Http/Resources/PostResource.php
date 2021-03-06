<?php

namespace App\Http\Resources;

use App\PostFile;
use App\PostLike;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'postid' => $this->postid,
            'userpost' => UserPostResuorce::make(User::find($this->userpost)),
            'postdescription' => $this->postdescription == null ? "null" : $this->postdescription,
            'posttag' => $this->posttag == '0' ? 'สาธารณะ' : "null",
            'postglat' => $this->postglat == null ? 'null' : $this->postglat,
            'postglng' => $this->postglng == null ? 'null' : $this->postglng,
            'postcommenttotal' => $this->postcommenttotal,
            'postliketotal' => $this->postliketotal,
            'postuserid' => $this->userid,
            'postuserlike' => auth()->check() ? PostLike::where('post_id',$this->postid)->where('user_id',auth()->user()->id)->count() == 0  ? false : true : false,
            'postdate' => Carbon::parse($this->postcreatedat)->locale('th')->diffForHumans(),
            'postfileimg' => PostFileResource::collection(PostFile::withoutTrashed()->where('post_id',$this->postid)->where('type_file','!=','pdf')->get()),
            'postfilepdf' => PostFileResource::collection(PostFile::withoutTrashed()->where('post_id',$this->postid)->where('type_file','=','pdf')->get())
        ];
    }
}
