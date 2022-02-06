<?php

namespace App\Http\Requests;

use App\Models\User;
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
            'status' => 'required|in:ACTIVE,INACTIVE',
        ];
    }

    public function withValidator($validator): void
    {

        $validator->after(function ($validator): void {
            if ($this->maxUsers()) {
                $validator->errors()->add('max_users', trans('validation.max_users', ['max_users' => $this->school->max_users]));
            }
        });
    }

    public function maxUsers(): bool
    {
        // check the max user of this school
        $quantityUsers = User::where('school_id', $this->school->id)->count();
        return $quantityUsers >= $this->school->max_users;
    }
}
