<?php

namespace App\Http\Controllers;

use App\Models\FinancialTransaction;
use App\Models\FinancialTransactionRatio;
use App\Http\Requests\FinancialTransactionRatioRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cookie;

class FinancialTransactionRatioController
{
    public function index(): JsonResponse
    {
        $user_group_id = Cookie::get('user_group_id');
        $financial_transactions = FinancialTransaction::where('user_group_id', $user_group_id)->get()->toArray();
        return response()->json(['data' => array_keys_to_camel($financial_transactions)]);
    }

    public function update(FinancialTransactionRatioRequest $request, $id)
    {
        // update financial transaction ratio
        $financial_transaction_ratio = FinancialTransactionRatio::find($id);
        $response = $financial_transaction_ratio->update([
            'financial_transaction_id' => $request->financialTransactionId,
            'ratio' => $request->ratio,
        ]);

        if (!$response) {
            return response()->json(['message' => '更新に失敗しました'], 500, [], JSON_UNESCAPED_UNICODE);
        }

        $financial_transaction_ratio = FinancialTransactionRatio::findOrfail($id)->toArray();
        return response()->json(['message' => '更新に成功しました', 'data' => array_keys_to_camel($financial_transaction_ratio)], 200, [], JSON_UNESCAPED_UNICODE);
    }
}
