<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupResource extends JsonResource
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
            'name' => $this->name,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'address3' => $this->address3,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country_id' => $this->country_id,
            'city' => $this->city,
            'status' => $this->status,
            'comment' => $this->comment,
        ];
    }
}
