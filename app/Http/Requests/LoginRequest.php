<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'password' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'ユーザー名が未入力です。',
            'password.required' => 'パスワードが未入力です。',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'data' => [],
            'status' => 'error',
            'errors' => $validator->errors()->toArray(),
        ];

        throw new HttpResponseException(response()->json($data, 422));
    }
}
