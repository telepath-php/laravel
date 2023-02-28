<?php

namespace Telepath\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSecretToken
{

    public function handle(Request $request, Closure $next): Response
    {
        $secretToken = config('telepath.secret_token') ?: null;

        abort_if(
            $secretToken !== null && $request->header('X-Telegram-Bot-Api-Secret-Token') !== $secretToken,
            403
        );

        return $next($request);
    }

}