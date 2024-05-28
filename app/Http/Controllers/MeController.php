<?php

namespace App\Http\Controllers;

use App\Models\UserGroup;
use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user()->toArray();
        $user_groups = UserGroup::where('id', $user['user_group_id'])->first()->toArray();
        $invite_code = $user_groups['invite_code'];
        $user['invite_code'] = $invite_code;
        $user = array_keys_to_camel($user);
        return response()->json([
            'data' => $user,
        ]);
    }
}
