<?php

namespace App\Services\Type\Registry;

use App\Services\Type\TypesChain;
use GraphQL\Type\Definition\ObjectType;

class TypesRegistry
{
    /**
     * @var TypesChain
     */
    private $typesChain;

    public function __construct(TypesChain $typesChain)
    {
        $this->typesChain = $typesChain;
    }

    public function getTypeByName($name): ?ObjectType
    {
        foreach ($this->typesChain->getTypes() as $type) {
            if ($type->getBaseTypeName() === $name) {
                return $type;
            }
        }

        return null;
    }

    public function getTypes(): array
    {
        return $this->typesChain->getTypes();
    }
}
