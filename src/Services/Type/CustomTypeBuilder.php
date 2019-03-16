<?php

namespace App\Services\Type;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class CustomTypeBuilder extends ObjectType
{
    /**
     * @var ServiceEntityRepository
     */
    private $typeRepository;

    /**
     * @var string
     */
    private $typeName;

    public function __construct($config, EntityRepository $repository, string $typeName)
    {
        $this->typeRepository = $repository;
        $this->typeName = $typeName;

        parent::__construct($config);
    }

    /**
     * @param $value
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return string|null
     */
    public function resolveField($value, $args, $context, ResolveInfo $info)
    {
        $resolverMethod = sprintf('method%s', ucfirst($info->fieldName));
        if (method_exists($this, $resolverMethod)) {
            return $this->{$resolverMethod}($value, $args, $context);
        } else {
            $methodField = sprintf('get%s', ucfirst($info->fieldName));
            return method_exists($value, $methodField) ? $value->{$methodField}() : null;
        }
    }
}
