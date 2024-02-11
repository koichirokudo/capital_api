<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Support\Facades\Log;

class SkipCsrfTokenForServerSideRequests extends Middleware
{
    public function handle($request, Closure $next)
    {

        // サーバサイドからのリクエストを特定するためのヘッダーがある場合は、
        // CSRFトークンのチェックをスキップする
        if ($request->headers->has('X-Server-Side-Request') === true) {
            return $next($request);
        }

        // 条件に合致しない場合は、CSRFトークンのチェックを行う
        return parent::handle($request, $next);
    }
}