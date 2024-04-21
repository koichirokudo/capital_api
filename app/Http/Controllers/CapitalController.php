<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapitalPostRequest;
use App\Models\Capital;
use App\Models\FinancialTransaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CapitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        // capital by user_id and group_id
        if ($request->has('userId') && $request->has('userGroupId')) {
            $capital = Capital::where('user_id', $request->userId)
                ->where('user_group_id', $request->userGroupId)
                ->with('user')
                ->get()
                ->toArray();
        } else if ($request->has('userId')) {
            // capital by user_id
            $capital = Capital::where('user_id', $request->userId)
                ->with('user')
                ->get()
                ->toArray();
        } else if ($request->has('userGroupId')) {
            // capital by group_id
            $capital = Capital::where('user_group_id', $request->userGroupId)
                ->with('user')
                ->get()
                ->toArray();
        } else {
            // all capitals
            $capital = Capital::all()->toArray();
        }
        return response()->json(['data' => array_keys_to_camel($capital)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CapitalPostRequest $request): JsonResponse
    {
        Capital::create([
            'capital_type' => $request->capitalType === config('constants.EXPENSES')
                ? config('constants.EXPENSES') : config('constants.INCOME'),
            'financial_transaction_id' => $request->financialTransactionId,
            'date' => $request->date,
            'user_id' => $request->userId,
            'user_group_id' => $request->userGroupId,
            'money' => $request->money,
            'note' => $request->note,
            'share' => $request->share,
            'settlement' => $request->settlement,
            'settlement_at' => $request->settlementAt,
        ]);

        return response()->json(['message' => '登録に成功しました'], 201, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Capital $capital)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Capital $capital)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CapitalPostRequest $request, $id)
    {
        // update capital
        $capital = Capital::findOrFail($id);
        $response = $capital->update([
            'capital_type' => $request->capitalType,
            'financial_transaction_id' => $request->financialTransactionId,
            'date' => $request->date,
            'user_id' => $request->userId,
            'group_id' => $request->userGroupId,
            'money' => $request->money,
            'note' => $request->note,
            'share' => $request->share,
            'settlement' => $request->settlement,
            'settlement_at' => $request->settlementAt,
        ]);

        if (!$response) {
            return response()->json(['message' => '更新に失敗しました'], 500, [], JSON_UNESCAPED_UNICODE);
        }

        $capital = Capital::findOrFail($id)->toArray();
        return response()->json(['message' => '更新に成功しました', 'data' => array_keys_to_camel($capital)], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // delete capital
        $capital = Capital::findOrFail($id);
        $capital->delete();

        return response()->json(null, 204);
    }
}
