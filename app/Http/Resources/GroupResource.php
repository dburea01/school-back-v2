<?php

namespace App\Http\Resources;

use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

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
        $users = User::whereIn('id', UserGroup::where('group_id', $this->id)->pluck('user_id'))->get();

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
            'users' => UserWithRoleResource::collection($users)
        ];
    }
}
