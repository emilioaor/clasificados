<?php

namespace App\Http\Requests\Index;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => 'required|min:6|max:60',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:20|confirmed',
            'phone' => 'required|regex:/^[0-9]{6,}$/',
        ];
    }
}
