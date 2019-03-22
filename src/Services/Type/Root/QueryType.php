<?php

namespace App\Services\Type\Root;

use App\Services\Type\Mapper\GraphTypeMapper;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    /**
     * QueryType constructor.
     * @param ResourceType $resourceType
     */
    public function __construct(ResourceType $resourceType)
    {
        $config = [
            'name' => 'Query',
            'fields' => [
                'node' => [
                    'type' => $resourceType,
                    'description' => 'Filter queries in app',
                    'args' => [
                        'limit' => Type::int(),
                        'offset' => Type::int(),
                    ],
                    'resolve' => function($value, $args) {
                        return $args;
                    }
                ],
            ],
        ];

        parent::__construct($config);
    }
}
