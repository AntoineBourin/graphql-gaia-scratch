<?php

namespace App\Services\Publisher;

use App\Entity\Issue;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IssuePublisher
{
    /**
     * @var MessageBusInterface
     */
    private $bus;
    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * IssuePublisher constructor.
     * @param MessageBusInterface $bus
     * @param NormalizerInterface $normalizer
     */
    public function __construct(MessageBusInterface $bus, NormalizerInterface $normalizer)
    {
        $this->bus = $bus;
        $this->normalizer = $normalizer;
    }

    public function publish(LifecycleEventArgs $args)
    {
        $update = new Update(sprintf('http://127.0.0.1:8000/project/%s', $args), json_encode($args));
        $this->bus->dispatch($update);
    }
}
