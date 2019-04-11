<?php

namespace App\Services\Dispatcher;

use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;

class IssueDispatcher implements DispatcherInterface
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * IssueDispatcher constructor.
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function dispatch($dataToDispatch, $topics): void
    {
        $mercureUpdate = new Update((array) $topics, $dataToDispatch);

        $this->bus->dispatch($mercureUpdate);
    }
}
