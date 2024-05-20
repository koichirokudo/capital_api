<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    //
    public function index(Request $request, $year, $month) {
        $userGroupId = Auth::user()->user_group_id;
        $settlements = Settlement::where('user_group_id', $userGroupId)
            ->where('year', $year)
            ->where('month', $month)
            ->get()->toArray();

        return response()->json(['data' => array_keys_to_camel($settlements)]);
    }
}
