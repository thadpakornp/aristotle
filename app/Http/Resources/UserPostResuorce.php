<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPostResuorce extends JsonResource
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
            'userid' => $this->id,
            'username' => $this->name,
            'usersurname' => $this->surname,
            'userprofile' => $this->profile == null ? "null" : asset('images/icon/'.$this->profile),
        ];
    }
}
