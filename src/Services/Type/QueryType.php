<?php

namespace App\Services\Type;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * QueryType constructor.
     * @param EntityManagerInterface $em
     * @param TypesChain $types
     */
    public function __construct(EntityManagerInterface $em, TypesChain $types)
    {
        $rootQueries = $this->getAllRootQueries($types);
        $this->em = $em;
        $config = [
            'name' => 'Query',
            'fields' => $rootQueries,
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    /**
     * Get all root queries mapped by TypesChain
     *
     * @param TypesChain $typesChain
     * @return array
     */
    public function getAllRootQueries(TypesChain $typesChain): array
    {
        $rootQueries = [];
        foreach ($typesChain->getTypes() as $type) {
            $rootQueries = array_merge($rootQueries, $type->getRootQuery());
        }

        return $rootQueries;
    }
}
