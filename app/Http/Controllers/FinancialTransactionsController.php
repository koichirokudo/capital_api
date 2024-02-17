<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\FinancialTransaction;

class FinancialTransactionsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->has('id')) {
            $expenses_item = FinancialTransaction::where('id', $request->id)->orderBy('id', 'asc')->get()->toArray();
        } else if ($request->has('type')) {
            $expenses_item = FinancialTransaction::where('type', $request->type)->orderBy('id', 'asc')->get()->toArray();
        } else {
            $expenses_item = FinancialTransaction::all()->toArray();
        }
        return response()->json(array_keys_to_camel($expenses_item));
    }
}
