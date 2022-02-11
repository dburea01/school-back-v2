<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreUserGroupRequest extends FormRequest
{
    protected $school;

    public function __construct()
    {
        $this->group = request()->route()->parameter('group');
    }

    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'uuid',
                'exists:users,id',
                Rule::unique('user_groups')->where(function ($query) {
                    return $query->where('group_id', $this->group->id);
                })
            ],
        ];
    }
}
