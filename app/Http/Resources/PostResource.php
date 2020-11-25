<?php

namespace App\Http\Resources;

use App\PostFile;
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
            'postdescription' => $this->description,
            'posttag' => $this->posttag == '0' ? 'สาธารณะ' : "null",
            'postglat' => $this->postglat == null ? 'null' : floatval($this->postglat),
            'postglng' => $this->postglng == null ? 'null' : floatval($this->postglng),
            'postcommenttotal' => $this->postcommenttotal,
            'postliketotal' => $this->postliketotal,
            'postdate' => Carbon::parse($this->postcreatedat)->locale('th')->diffForHumans(),
            'postfile' => PostFile::where('post_id',$this->postid)->count() > 0 ? PostFileResource::collection(PostFile::withoutTrashed()->where('post_id',$this->postid)->get()) : 'null'
        ];
    }
}
