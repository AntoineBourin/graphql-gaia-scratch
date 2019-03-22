<?php

namespace App\Services\Type\Root;

use App\Services\Type\Mapper\GraphTypeMapper;
use GraphQL\Type\Definition\ObjectType;

class QueryType extends ObjectType
{
    /**
     * QueryType constructor.
     * @param GraphTypeMapper $graphTypeMapper
     */
    public function __construct(GraphTypeMapper $graphTypeMapper)
    {
        $rootQueries = $graphTypeMapper->getBasicsQueries();
        $config = [
            'name' => 'Query',
            'fields' => $rootQueries,
        ];

        parent::__construct($config);
    }
}
