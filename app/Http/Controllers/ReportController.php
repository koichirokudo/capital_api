<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getYearlyReport(Request $request): JsonResponse
    {
        $user_id = $request->input('user_id');

        $user = User::find($user_id);

        // 年ごとの収支を計算
        $years = DB::table('capitals')
            ->select(DB::raw("TO_CHAR(date::DATE, 'YYYY') as year"))
            ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
            ->where('users.id', $user->id)
            ->distinct()
            ->pluck('year');

        $result = [];
        foreach ($years as $year) {
            // 収入を計算
            $income = DB::table('capitals')
                ->select([
                    DB::raw('SUM(money) as total'),
                    DB::raw("TO_CHAR(date::DATE, 'Month') as month")
                ])
                ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
                ->whereYear('date', $year)
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', config('constants.INCOME'))
                ->groupBy('month')
                ->get();

            // 支出を計算
            $expenses = DB::table('capitals')
                ->select([
                    DB::raw('SUM(money) as total'),
                    DB::raw("TO_CHAR(date::DATE, 'Month') as month")
                ])
                ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
                ->whereYear('date', $year)
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', config('constants.EXPENSES'))
                ->groupBy('month')
                ->get();

            $result[] = [
                'year' => $year,
                'userId' => $user->id,
                'userGroupId' => $user->userGroup_id,
                'incomeTotal' => $income->sum('total'),
                'incomeDetails' => $income->pluck('total', 'month')->all(),
                'expensesTotal' => $expenses->sum('total'),
                'expensesDetails' => $expenses->pluck('total', 'month')->all(),
            ];
        }

        return response()->json(['data' => $result]);
    }

    public function getMonthlyReport(Request $request): JsonResponse
    {
        $year = $request->input('year');
        $user_id = $request->input('user_id');

        $user = User::find($user_id);

        // 月ごとの収支を計算
        $monthly = DB::table('capitals')
            ->select(DB::raw("TO_CHAR(date::DATE, 'YYYY-MM') as month"))
            ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
            ->where('users.id', $user->id)
            ->distinct()
            ->pluck('month');

        $result = [];
        foreach ($monthly as $month) {
            // 収入を計算
            $income = DB::table('capitals')
                ->select(
                    'ft.id',
                    'ft.label as category',
                    DB::raw('SUM(money) as total'),
                )
                ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
                ->leftJoin('financial_transactions as ft', 'capitals.financial_transaction_id', '=', 'ft.id')
                ->whereRaw("date_trunc('month', date) = TO_DATE(?, 'YYYY-MM')", [$month])
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', config('constants.INCOME'))
                ->groupBy('ft.id', 'ft.label')
                ->orderBy('ft.id')
                ->get();

            // 支出を計算
            $expenses = DB::table('capitals')
                ->select(
                    'ft.id',
                    'ft.label as category',
                    DB::raw('SUM(money) as total'),
                )
                ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
                ->leftJoin('financial_transactions as ft', 'capitals.financial_transaction_id', '=', 'ft.id')
                ->whereRaw("date_trunc('month', date) = TO_DATE(?, 'YYYY-MM')", [$month])
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', config('constants.EXPENSES'))
                ->groupBy('ft.id', 'ft.label')
                ->orderBy('ft.id')
                ->get();

            $result[] = [
                'year' => $year,
                'month' => $month,
                'userId' => $user->id,
                'userGroupId' => $user->user_group_id,
                'incomeTotal' => $income->sum('total'),
                'incomeDetails' => $income->pluck('total', 'category')->all(),
                'expensesTotal' => $expenses->sum('total'),
                'expensesDetails' => $expenses->pluck('total', 'category')->all(),
            ];
        }

        return response()->json(['data' => $result]);
    }
}
