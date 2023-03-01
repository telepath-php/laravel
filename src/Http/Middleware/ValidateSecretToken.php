<?php

namespace Telepath\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSecretToken
{

    public function handle(Request $request, Closure $next): Response
    {
        $secretToken = config('telepath.webhook.secret') ?: null;

        abort_unless(
            $secretToken === null
                || $request->header('X-Telegram-Bot-Api-Secret-Token') === $secretToken,
            403,
            'Forbidden'
        );

        return $next($request);
    }

}