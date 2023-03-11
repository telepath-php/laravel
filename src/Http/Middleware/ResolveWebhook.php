<?php

namespace Telepath\Laravel\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;
use Telepath\Bot;
use Telepath\Laravel\Contracts\WebhookResolver;
use Telepath\Laravel\Facades\Telepath;

class ResolveWebhook
{

    public function __construct(
        protected WebhookResolver $resolver
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $name = $this->resolver->resolve(
            $request->route('secret')
        );

        abort_if($name === null, 404);

        App::scoped(Bot::class, fn() => Telepath::bot($name));

        return $next($request);
    }

}