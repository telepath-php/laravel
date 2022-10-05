<?php

namespace Telepath\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\IpUtils;

class TrustTelegramNetwork
{

    /**
     * Trusted Telegram IP addresses
     * @see https://core.telegram.org/bots/webhooks#the-short-version
     * @var array
     */
    protected array $trustedTelegramNets = [
        '149.154.160.0/20',
        '91.108.4.0/22',
    ];

    /**
     * Local networks for debugging
     * @var array|string[]
     */
    protected array $localIpNets = [
        '127.0.0.1/32',
        '10.0.0.0/8',
        '172.16.0.0/12',
        '192.168.0.0/16'
    ];

    public function handle(Request $request, Closure $next)
    {
        if (App::environment('local') && IpUtils::checkIp($request->ip(), $this->localIpNets)) {
            return $next($request);
        }

        if (IpUtils::checkIp($request->ip(), $this->trustedTelegramNets)) {
            return $next($request);
        }

        abort(403);
    }

}