<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class FinancialTransactionRatioRequest extends FormRequest
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
            'userGroupId' => 'required|integer',
            'financialTransactionId' => 'required|integer',
            'ratio' => 'required|integer',
        ];
    }

    public function messages(): array
    {
        return [
            "userGroupId.required" => "グループIDを入力してください",
            "userGroupId.integer" => "グループIDは数値で入力してください",
            "financialTransactionId.required" => "支出項目を選択してください",
            "financialTransactionId.integer" => "支出項目は数値で入力してください",
            "ratio.required" => "割合を入力してください",
            "ratio.integer" => "割合は数値で入力してください",
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
