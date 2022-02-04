<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'school_id' => $this->school_id,
            'role_id' => $this->role_id,
            'last_name' => $this->last_name,
            'first_name' => $this->first_name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'status' => $this->status,
            'comment' => $this->comment
        ];
    }
}
