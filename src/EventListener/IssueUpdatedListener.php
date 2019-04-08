<?php

namespace App\EventListener;

use App\Entity\Issue;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class IssueUpdatedListener
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * IssueUpdatedListener constructor.
     * @param MessageBusInterface $bus
     */
    public function __construct(MessageBusInterface $bus)
    {

        $this->bus = $bus;
    }

    public function preUpdate(Issue $issue, LifecycleEventArgs $args)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer([$normalizer], [new JsonEncoder()]);

        $serializedIssue = $serializer->serialize($issue, 'json', ['groups' => ['public']]);
        $projectId = $issue->getState()->getProject()->getId();
        $mercureUpdate = new Update(sprintf('http://gaiaticket.com/project/%s', $projectId), $serializedIssue);

        $this->bus->dispatch($mercureUpdate);
    }
}
