<?php

namespace App\Services\Type;

class TypesChain
{
    private $types;

    /**
     * TypesChain constructor.
     */
    public function __construct()
    {
        $this->types = [];
    }

    /**
     * @param GraphCustomTypeInterface $type
     */
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
