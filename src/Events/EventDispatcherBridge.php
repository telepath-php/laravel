<?php

namespace Telepath\Laravel\Events;

use Illuminate\Contracts\Events\Dispatcher as LaravelEventDispatcher;
use Psr\EventDispatcher\EventDispatcherInterface;

class EventDispatcherBridge implements EventDispatcherInterface
{

    public function __construct(
        protected LaravelEventDispatcher $dispatcher,
    ) {}

    public function dispatch(object $event)
    {
        $this->dispatcher->dispatch($event);
    }

}