<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * ログイン認証
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!Auth::attempt($credentials)) {
            // 認証失敗
            return response()->json([
                'message' => 'ユーザー名またはパスワードが違います。'
            ], 401);
        }

        $user = Auth::user();
        $userGroupId = $user->user_group_id;

        $minutes = 60;
        return response()->json([
            'message' => 'ログインに成功しました。'
        ])->withCookie(cookie('user_group_id', $userGroupId, $minutes));
    }
}
