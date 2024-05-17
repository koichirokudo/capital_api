<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculateController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $user = User::where('id', $userId)->first();
        return response()->json(['data' => array_keys_to_camel($user)]);
    }
}
