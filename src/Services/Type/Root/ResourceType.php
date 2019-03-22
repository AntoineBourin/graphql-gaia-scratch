<?php

namespace App\Services\Type\Root;

use App\Services\Type\Mapper\GraphTypeMapper;
use GraphQL\Type\Definition\ObjectType;

class ResourceType extends ObjectType
{
    /**
     * ResourceType constructor.
     * @param GraphTypeMapper $graphTypeMapper
     */
    public function __construct(GraphTypeMapper $graphTypeMapper)
    {
        $rootQueries = $graphTypeMapper->getBasicsQueries();
        $config = [
            'name' => 'resource',
            'fields' => $rootQueries,
        ];
        parent::__construct($config);
    }
}
