<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'userrole' => auth()->check() ? $this->userRole() : 'null'
        ];
    }

    private function userRole()
    {
        if(auth()->user()->hasRole('admin')){
            return 'admin';
        } elseif (auth()->user()->hasRole('store')){
            return 'store';
        } else {
            return 'user';
        }
    }
}
