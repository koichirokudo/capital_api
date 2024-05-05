<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use App\Mail\UserRegisterMail;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * create user
     * @param UserPostRequest $request
     * @return JsonResponse
     */
    public function create(UserPostRequest $request): JsonResponse
    {
        $authType = 1;
        UserGroup::create([
            'group_name' => $request->name,
            'start_day' => 1
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_group_id' => UserGroup::where('group_name', $request->name)->first()->id,
            'token' => Str::random(60),
            'auth_type' => $authType,
            'delete' => false,
        ]);

        $user = User::where('email', $request->email)->first();
        $token = User::where('email', $request->email)->first()->token;
        $email = $request->email;
        $url = env('APP_URL') . '/users/verify?token=' . $token;
        Mail::to($email)->send(new UserRegisterMail($user, $url));

        return response()->json(['message' => '登録に成功しました'], 201, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * verify email
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        $token = $request->token;
        $user = User::where('token', $token)->first();
        if ($user && $user->email_verified_at === null) {
            $user->email_verified_at = now();
            $user->save();
            return response()->json(['status' => 'success', 'message' => 'メールアドレスの認証が完了しました'], 200, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json(['status' => 'error', 'message' => '無効なトークンです'], 200, [], JSON_UNESCAPED_UNICODE);
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
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
