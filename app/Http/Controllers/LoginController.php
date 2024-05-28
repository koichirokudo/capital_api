<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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

        // ユーザー名でユーザーを取得
        $user = User::where('name', $credentials['name'])->firstOrFail();

        // ユーザーが存在しない、または仮登録状態の場合はエラー
        if (!$user || !$user->email_verified_at) {
            return response()->json([
                'message' => 'ユーザー名またはパスワードが違います。'
            ], 401);
        }

        // パスワードが一致しない場合はエラー
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'ユーザー名またはパスワードが違います。'
            ], 401);
        }

        // ログイン
        Auth::login($user);

        return response()->json([
            'message' => 'ログインに成功しました。'
        ]);
    }
}
