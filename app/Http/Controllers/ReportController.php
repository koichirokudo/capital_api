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
            ->leftJoin('users', 'capitals.group_id', '=', 'users.group_id')
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
                ->leftJoin('users', 'capitals.group_id', '=', 'users.group_id')
                ->whereYear('date', $year)
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', '収入')
                ->groupBy('month')
                ->get();

            // 支出を計算
            $expenses = DB::table('capitals')
                ->select([
                    DB::raw('SUM(money) as total'),
                    DB::raw("TO_CHAR(date::DATE, 'Month') as month")
                ])
                ->leftJoin('users', 'capitals.group_id', '=', 'users.group_id')
                ->whereYear('date', $year)
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', '支出')
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
            ->leftJoin('users', 'capitals.group_id', '=', 'users.group_id')
            ->where('users.id', $user->id)
            ->distinct()
            ->pluck('month');

        $result = [];
        foreach ($monthly as $month) {
            // 収入を計算
            $income = DB::table('capitals')
                ->select(
                    'expenses_item as category',
                    DB::raw('SUM(money) as total'),
                )
                ->leftJoin('users', 'capitals.group_id', '=', 'users.group_id')
                ->whereRaw("date_trunc('month', date) = TO_DATE(?, 'YYYY-MM')", [$month])
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', '収入')
                ->groupBy('expenses_item')
                ->get();

            // 支出を計算
            $expenses = DB::table('capitals')
                ->select(
                    'expenses_item as category',
                    DB::raw('SUM(money) as total'),
                )
                ->leftJoin('users', 'capitals.group_id', '=', 'users.group_id')
                ->whereRaw("date_trunc('month', date) = TO_DATE(?, 'YYYY-MM')", [$month])
                ->where('users.id', '=', $user->id)
                ->where('capital_type', '=', '支出')
                ->groupBy('expenses_item')
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
