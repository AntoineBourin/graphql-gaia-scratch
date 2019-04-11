<?php

namespace App\EventListener;

use App\Entity\Issue;
use App\Normalizer\GroupSerializer;
use App\Services\Dispatcher\IssueDispatcher;
use Doctrine\ORM\Event\LifecycleEventArgs;

class IssueUpdatedListener
{
    /**
     * @var IssueDispatcher
     */
    private $issueDispatcher;
    /**
     * @var GroupSerializer
     */
    private $serializer;

    /**
     * IssueUpdatedListener constructor.
     * @param IssueDispatcher $issueDispatcher
     * @param GroupSerializer $serializer
     */
    public function __construct(IssueDispatcher $issueDispatcher, GroupSerializer $serializer)
    {
        $this->issueDispatcher = $issueDispatcher;
        $this->serializer = $serializer;
    }

    /**
     * @param Issue $issue
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(Issue $issue, LifecycleEventArgs $args)
    {
        $issueSerialized = $this->serializer->serializeWithGroups($issue, 'json', ['lightPublish']);
        $topicUpdate = sprintf('http://gaiaticket.com/project/%s', $issue->getState()->getProject()->getId());

        $this->issueDispatcher->dispatch($issueSerialized, $topicUpdate);
    }
}
