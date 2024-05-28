<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserEditRequest;
use App\Http\Requests\UserPostRequest;
use App\Mail\UserRegisterMail;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();
        $user = User::where('id', $userId)->first();
        return response()->json(['data' => array_keys_to_camel($user)]);
    }

    /**
     * create user
     * @param UserPostRequest $request
     * @return JsonResponse
     */
    public function create(UserPostRequest $request): JsonResponse
    {
        try {
            $auth_type = 1;
            if ($request->filled('inviteCode')) {
                $auth_type = 2;
                $user_group = UserGroup::where('invite_code', $request->inviteCode)->first();
                if ($user_group->count() === 0) {
                    return response()->json(['message' => '招待コードが無効です'], 400, [], JSON_UNESCAPED_UNICODE);
                }
                $user_group_id = $user_group->id;
            } else {
                UserGroup::create([
                    'invite_code' => Str::random(60),
                    'group_name' => $request->name,
                    'start_day' => 1
                ]);
                $user_group_id = UserGroup::where('group_name', $request->name)->first()->id;
            }

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'user_group_id' => $user_group_id,
                'token' => Str::random(60),
                'auth_type' => $auth_type,
                'delete' => false,
            ]);

            $user = User::where('email', $request->email)->first();
            $token = User::where('email', $request->email)->first()->token;
            $email = $request->email;
            $url = env('APP_URL') . '/users/verify?token=' . $token;
            Mail::to($email)->send(new UserRegisterMail($user, $url));

            return response()->json(['message' => 'ユーザーの登録に成功しました'], 201, [], JSON_UNESCAPED_UNICODE);
        } catch (\Exception $e) {
            if ($e->getCode() == '23505') {
                return response()->json(['message' => '既に登録されているメールアドレスです'], 400, [], JSON_UNESCAPED_UNICODE);
            }
            return response()->json(['message' => 'ユーザーの作成に失敗しました'], 400, [], JSON_UNESCAPED_UNICODE);
        }
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
    public function update(UserEditRequest $request)
    {
        $id = Auth::id();
        $user = User::findOrFail($id);

        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        $response = $user->update($updateData);

        if (!$response) {
            return response()->json(['message' => '更新に失敗しました'], 400, [], JSON_UNESCAPED_UNICODE);
        }

        $user = User::findOrFail($id)->toArray();
        return response()->json(['message' => '更新に成功しました', 'data' => array_keys_to_camel($user)], 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
