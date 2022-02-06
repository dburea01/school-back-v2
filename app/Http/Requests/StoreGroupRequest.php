<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGroupRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'name' => 'required',
            'address1' => 'required',
            'city' => 'required',
            'country_id' => 'required|size:2',
            'zip_code' => 'required',
            'status' => 'required|in:ACTIVE,INACTIVE',
        ];
    }
}
