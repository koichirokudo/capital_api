<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class LogoutController extends Controller
{
    public function __invoke(Request $request)
    {
        Log::debug('ログアウト処理を実行します');
        $request->user()->tokens()->delete();

        // クライアント側のセッションをクリア
        Cookie::queue(Cookie::forget('capital_session'));
        Cookie::queue(Cookie::forget('XSRF-TOKEN'));

        return response()->json(['message' => 'ログアウトしました']);
    }
}
