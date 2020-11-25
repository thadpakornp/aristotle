<?php

namespace App\Http\Resources;

use App\CommentFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'commentid' => $this->id,
            'commentuser' => UserPostResuorce::make(User::find($this->user_id)),
            'commentdescription' => $this->description,
            'commentfile' => CommentFile::withoutTrashed()->where('comment_id',$this->id)->count() > 0 ? CommentFileResource::collection(CommentFile::withoutTrashed()->where('comment_id',$this->id)->get()) : 'null',
            'commenttime' => Carbon::parse($this->created_at)->locale('th')->diffForHumans()
        ];
    }
}
