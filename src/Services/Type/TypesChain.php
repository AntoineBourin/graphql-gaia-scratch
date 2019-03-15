<?php

namespace App\Services\Type;

class TypesChain
{
    private $types;

    public function __construct()
    {
        $this->types = [];
    }

    public function addType(GraphCustomTypeInterface $type): void
    {
        $this->types[] = $type;
    }

    /**
     * @return GraphCustomTypeInterface[]
     */
    public function getTypes(): array
    {
        return $this->types;
    }
}
