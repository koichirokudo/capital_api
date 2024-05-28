<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'email',
            'name' => 'string',
            'password' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            "email.string" => "メールアドレスは文字列で入力してください",
            "name.string" => "名前は文字列で入力してください",
            "password.string" => "パスワードは文字列で入力してください",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'status' => 'error',
            'summary' => 'validation error',
            'errors' => $validator->errors()
        ];

        throw new HttpResponseException(
            response()->json($data, 422)
        );
    }
}
