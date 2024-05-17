<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class UserGroupsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_group_id = Auth::user()->user_group_id;
        $user_group = UserGroup::where('id', $user_group_id)->first()->toArray();
        return response()->json(['data' => array_keys_to_camel($user_group)]);
    }

    public function group()
    {
        $user_group_id = Auth::user()->user_group_id;
        $users = User::where('user_group_id', $user_group_id)->get()->toArray();
        return response()->json(['data' => array_keys_to_camel($users)]);
    }

    /**
     * verify email
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request): JsonResponse
    {
        //
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
