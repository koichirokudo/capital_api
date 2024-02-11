<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user()->toArray();
        $user = array_keys_to_camel($user);
        return response()->json([
            'data' => $user,
        ]);
    }
}
