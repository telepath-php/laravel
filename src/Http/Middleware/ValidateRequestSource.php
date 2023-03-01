<?php

namespace Telepath\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpFoundation\Response;

class ValidateRequestSource
{

    protected array $telegramSubnets = [
        '149.154.160.0/20',
        '91.108.4.0/22',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            IpUtils::checkIp($request->ip(), $this->telegramSubnets),
            403,
            'Forbidden'
        );

        return $next($request);
    }

}