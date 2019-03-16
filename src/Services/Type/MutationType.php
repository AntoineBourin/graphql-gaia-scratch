<?php

namespace App\Services\Type;

use App\Services\Type\Mapper\GraphTypeMapper;
use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    /**
     * QueryType constructor.
     * @param GraphTypeMapper $graphTypeMapper
     */
    public function __construct(GraphTypeMapper $graphTypeMapper)
    {
        $rootMutations = $graphTypeMapper->getBasicsMutations();
        $config = [
            'name' => 'Mutation',
            'fields' => $rootMutations,
        ];

        parent::__construct($config);
    }
}
