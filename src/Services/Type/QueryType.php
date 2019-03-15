<?php

namespace App\Services\Type;

use GraphQL\Type\Definition\ObjectType;

class QueryType extends ObjectType
{
    /**
     * QueryType constructor.
     * @param TypesChain $types
     */
    public function __construct(TypesChain $types)
    {
        $rootQueries = $this->getAllRootQueries($types);
        $config = [
            'name' => 'Query',
            'fields' => $rootQueries,
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
