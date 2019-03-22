<?php

namespace App\Services\Type\Registry;

use GraphQL\Type\Definition\ObjectType;

interface TypesRegistryInterface
{
    /**
     * @param $name
     * @return ObjectType|null
     */
    public function getTypeByName($name): ?ObjectType;

    /**
     * @return array
     */
    public function getTypes(): array;
}
