<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class SignupUserRequest extends FormRequest
{
    // /**
    //  * Determine if the user is authorized to make this request.
    //  *
    //  * @return bool
    //  */
    // public function authorize()
    // {
    //     return true;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'name' => 'required|string|max:255',
            'password' => 'required|min:6|confirmed',
            'profile_picture' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'dob'=>'date|date_format:Y-m-d|before:today',
            'gender'=>'alpha|max:20',

        ];
    }
     /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
          ], 422));
    }

    public function messages()

    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name should be a string',
            'name.max' => 'Name cannot be longer than 70 characters',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid Email Syntax',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password should include atleast 6 characters',
            'profile_pic.image' => 'Please upload valid file',
            'dob.date' => 'Follow YYYY-MM-DD format for DOB', 
        ];
    }

    public function filters()
    {
        return [
            'email' => 'trim|lowercase',
            'name' => 'trim|capitalize|escape'
        ];
    }
    
}
