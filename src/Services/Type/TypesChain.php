<?php

namespace App\Services\Type;

class TypesChain
{
    private $types;

    public function __construct()
    {
        $this->types = [];
    }

    public function addType($type)
    {
        $this->types[] = $type;
    }

    public function getTypes()
    {
        return $this->types;
    }
}
