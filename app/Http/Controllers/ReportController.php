<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getYearlyReport(Request $request): JsonResponse
    {
        $user = Auth::user();

        // 年ごとの収支を計算
        $years = DB::table('capitals')
            ->select(DB::raw("TO_CHAR(date::DATE, 'YYYY') as year"))
            ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
            ->where('users.id', $user->id)
            ->distinct()
            ->pluck('year');

        $default_data = [
            '1' => 0,
            '2' => 0,
            '3' => 0,
            '4' => 0,
            '5' => 0,
            '6' => 0,
            '7' => 0,
            '8' => 0,
            '9' => 0,
            '10' => 0,
            '11' => 0,
            '12' => 0,
        ];

        foreach ($years as $year) {
            $income_default_data = $default_data;

            // 収入を計算
            $income = DB::table('capitals')
                ->select([
                    DB::raw("TO_CHAR(date::DATE, 'FMMM') as month"),
                    DB::raw('SUM(money) as total'),
                ])
                ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
                ->whereYear('date', $year)
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', config('constants.INCOME'))
                ->where('share', true)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total', 'month')
                ->all();
            $income_total = array_sum($income);
            $income_details = array_replace($income_default_data, $income);

            // 支出を計算
            $expenses_default_data = $default_data;
            $expenses = DB::table('capitals')
                ->select([
                    DB::raw("TO_CHAR(date::DATE, 'FMMM') as month"),
                    DB::raw('SUM(money) as total'),
                ])
                ->leftJoin('users', 'capitals.user_group_id', '=', 'users.user_group_id')
                ->whereYear('date', $year)
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', config('constants.EXPENSES'))
                ->where('share', true)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('total', 'month')
                ->all();
            $expenses_total = array_sum($expenses);
            $expenses_details = array_replace($expenses_default_data, $expenses);

            $result[] = [
                'year' => $year,
                'userId' => $user->id,
                'userGroupId' => $user->userGroup_id,
                'incomeTotal' => $income_total,
                'incomeDetails' => $income_details,
                'expensesTotal' => $expenses_total,
                'expensesDetails' => $expenses_details,
            ];
        }

        return response()->json(['data' => $result]);
    }

    public function getMonthlyReport(Request $request): JsonResponse
    {
        $user = Auth::user();

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
                ->where('share', true)
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
                ->where('share', true)
                ->groupBy('ft.id', 'ft.label')
                ->orderBy('ft.id')
                ->get();

            $result[] = [
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
