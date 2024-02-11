<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CapitalPostRequest extends FormRequest
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
            "capitalType" => "required|string",
            "expensesItem" => "required|string",
            "date" => "required|string",
            "groupId" => "required|integer",
            "money" => "required|integer",
            "name" => "required|string",
            "note" => "string|nullable",
            "share" => "boolean",
            "settlement" => "required|boolean",
            "settlementAt" => "string|nullable",
        ];
    }
    public function messages()
    {
        return [
            "capitalType.required" => "収支の種類を選択してください",
            "capitalType.string" => "収支の種類は文字列で入力してください",
            "expensesItem.required" => "支出項目を選択してください",
            "expensesItem.string" => "支出項目は文字列で入力してください",
            "date.required" => "日付を選択してください",
            "date.string" => "日付は文字列で入力してください",
            "groupId.required" => "グループIDを入力してください",
            "groupId.integer" => "グループIDは整数で入力してください",
            "money.required" => "金額を入力してください",
            "money.integer" => "金額は整数で入力してください",
            "name.required" => "名前を入力してください",
            "name.string" => "名前は文字列で入力してください",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $data = [
            'status' => 'error',
            'summary' => 'validation error.',
            'errors' => $validator->errors(),
        ];

        throw new HttpResponseException(response()->json($data, 422));
    }
}