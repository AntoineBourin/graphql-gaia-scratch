<?php

namespace App\Services\Dispatcher;

interface DispatcherInterface
{
    public function dispatch($dataToDispatch, $topics): void;
}
