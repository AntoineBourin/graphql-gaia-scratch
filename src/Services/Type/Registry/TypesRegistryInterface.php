<?php

namespace App\Services\Type\Registry;

use GraphQL\Type\Definition\ObjectType;

interface TypesRegistryInterface
{
    public function getTypeByName($name): ?ObjectType;
    public function getTypes(): array;
}
