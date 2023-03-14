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

    protected array $localSubnets = [
        '127.0.0.1/32',
        '192.168.0.0/16',
        '172.16.0.0/12',
        '10.0.0.0/8',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        abort_unless(
            $this->isTelegramSubnet($request->ip())
            || $this->allowLocalSubnets() && $this->isLocalSubnet($request->ip()),
            403,
            'Forbidden'
        );

        return $next($request);
    }

    protected function allowLocalSubnets(): bool
    {
        return app()->environment('local')
            || config('telepath.webhook.allow_local_subnets', false);
    }

    protected function isTelegramSubnet(string $ip): bool
    {
        return IpUtils::checkIp($ip, $this->telegramSubnets);
    }

    protected function isLocalSubnet(string $ip): bool
    {
        return IpUtils::checkIp($ip, $this->localSubnets);
    }

}