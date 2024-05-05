<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UserPostRequest extends FormRequest
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
            'email' => 'required|email',
            'name' => 'required|string',
            'password' => 'required|string',
            'inviteCode' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            "email.required" => "メールアドレスを入力してください",
            "email.string" => "メールアドレスは文字列で入力してください",
            "name.required" => "名前を入力してください",
            "name.string" => "名前は文字列で入力してください",
            "password.required" => "パスワードを入力してください",
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
