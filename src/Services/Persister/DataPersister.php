<?php

namespace App\Services\Persister;

use Symfony\Bridge\Doctrine\ManagerRegistry;

class DataPersister
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function getManager($data)
    {
        return \is_object($data) ? $this->managerRegistry->getManagerForClass(get_class($data)) : null;
    }

    public function supports($data): bool
    {
        return null !== $this->getManager($data);
    }
}
