<?php

namespace App\Http\Controllers;

use App\Http\Requests\CapitalPostRequest;
use App\Models\Capital;
use App\Models\Settlement;
use App\Models\SettlementDetail;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CapitalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $userGroupId = Auth::user()->user_group_id;
        $userId = Auth::id();

        // capital by user_id and group_id
        $capitals = Capital::where(function ($query) use ($userId, $userGroupId) {
            $query->where('user_id', $userId)  // ログインユーザー自身のレコードを取得
            ->orWhere(function ($query) use ($userGroupId) {  // または
                $query->where('user_group_id', $userGroupId)  // ログインユーザーが所属するグループの
                ->where('share', true);  // 共有レコードを取得
            });
        })->with(['user' => function ($query) {
            $query->select('id', 'name');
            $query->where('delete', false);
        }])->get()->toArray();
        return response()->json(['data' => array_keys_to_camel($capitals)]);
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
            'settlement_id' => null,
            'date' => $request->date,
            'user_id' => $request->userId,
            'user_group_id' => $request->userGroupId,
            'money' => $request->money,
            'note' => $request->note,
            'share' => $request->share,
        ]);

        return response()->json(['message' => '登録に成功しました'], 201, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * @param Request $request
     * @param $year
     * @param $month
     * @return JsonResponse
     *
     * @example Response
     * {
     *     "label": "食費",
     *     "paid": {
     *       "test_user1": 6000,
     *       "test_user2": 5000,
     *       "perPerson": 5500,
     *       "total": 11000,
     *      },
     *     "paymentPlan": {
     *       "test_user1": 0,
     *       "test_user2": 500,
     *     }
     * }
     */
    public function calculate(Request $request, $year, $month): JsonResponse
    {
        $userGroupId = Auth::user()->user_group_id;
        $capitals = Capital::select('financial_transactions.id', 'financial_transactions.label', 'users.name', DB::raw('SUM(MONEY) as money'))
            ->leftJoin('financial_transactions', 'financial_transactions.id', '=', 'capitals.financial_transaction_id')
            ->leftJoin('users', 'users.id', '=', 'capitals.user_id')
            ->whereYear('date', (int)$year)
            ->whereMonth('date', (int)$month)
            ->where('capitals.user_group_id', (int)$userGroupId)
            ->where('share', true)
            ->groupBy('financial_transactions.id', 'financial_transactions.label', 'users.name')
            ->havingRaw('SUM(MONEY) <> 0')
            ->orderBy('financial_transactions.id')
            ->get();
        $users = User::select('id', 'name')->where('user_group_id', $userGroupId)->where('delete', false)->get()->toArray();

        $payment_by_category = [];
        foreach ($capitals as $capital) {
            $payment_by_category[$capital->id]['label'] = $capital->label;
            $payment_by_category[$capital->id]['paid'][$capital->name] = $capital->money;
        }

        // 各カテゴリの支払い合計を計算
        $payment_plan_total = [];
        foreach ($payment_by_category as $category_id => $payments) {
            $payment_by_category[$category_id]['paid']['total'] = array_sum($payments['paid']);
            // TODO:FinancialTransactionRatioモデルを使って支払い比率を取得
            $payment_by_category[$category_id]['paid']['perPerson'] = (int)floor($payment_by_category[$category_id]['paid']['total'] * config('constants.RATIO'));
            foreach ($users as $user) {
                if (!isset($payment_by_category[$category_id]['paid'][$user['name']])) {
                    $payment_by_category[$category_id]['paid'][$user['name']] = 0;
                    $payment_by_category[$category_id]['paymentPlan'][$user['name']] = 0;
                }
                $money = $payment_by_category[$category_id]['paid']['perPerson'] - $payment_by_category[$category_id]['paid'][$user['name']];
                if ($money > 0) {
                    $payment_by_category[$category_id]['paymentPlan'][$user['name']] = $money;
                } else {
                    $payment_by_category[$category_id]['paymentPlan'][$user['name']] = 0;
                }

                if (!isset($payment_plan_total[$user['name']])) {
                    $payment_plan_total[$user['name']] = 0;
                }
                $payment_plan_total[$user['name']] += $payment_by_category[$category_id]['paymentPlan'][$user['name']];
            }
        }

        return response()->json([
            'data' => [
                'paymentByCategory' => $payment_by_category,
                'paymentPlanTotal' => $payment_plan_total,
                'users' => $users,
            ]
        ]);

    }

    public function decision(Request $request)
    {
        $user_group_id = Auth::user()->user_group_id;

        $settlement = Settlement::create([
            'user_group_id' => $user_group_id,
            'year' => $request->year,
            'month' => $request->month,
            'settled' => true,
        ]);

        SettlementDetail::create([
            'settlement_id' => $settlement->id,
            'payer_id' => $request->payerId,
            'payee_id' => $request->payeeId,
            'amount' => $request->amount,
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
