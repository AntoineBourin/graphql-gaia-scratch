<?php

namespace App\Services\Type;

use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;

interface GraphCustomTypeInterface
{
    public function getTypeRepository(): EntityRepository;
    public function getBaseTypeName(): string;
    public function getInputType(): InputType;
    public function hasResourceAccess($args, $context): bool;
    public function getCustomQueries(): array;
    public function getCustomMutations(): array;
}
