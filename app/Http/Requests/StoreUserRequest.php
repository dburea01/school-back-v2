<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
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
            'role_id' => 'required|exists:roles,id',
            'last_name' => 'required',
            'first_name' => 'required',
            'birth_date' => 'date_format:d/m/Y',
            'gender_id' => 'required_if:role_id,STUDENT|in:1,2',
            'status' => 'required|in:ACTIVE,INACTIVE'
        ];
    }
}
