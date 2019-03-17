<?php

namespace App\Services\Type;

use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;

interface GraphCustomTypeInterface
{
    public function getTypeRepository(): EntityRepository;
    public function getBaseTypeName(): string;
    public function getInputType(): InputType;
}
