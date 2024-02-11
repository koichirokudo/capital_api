<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ExpensesItems;

class ExpensesItemsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        if ($request->has('id')) {
            $expenses_item = ExpensesItems::where('id', $request->id)->get()->toArray();
        } else if ($request->has('type')) {
            $expenses_item = ExpensesItems::where('type', $request->type)->get()->toArray();
        } else {
            $expenses_item = ExpensesItems::all()->toArray();
        }
        return response()->json(array_keys_to_camel($expenses_item));
    }
}