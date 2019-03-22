<?php

namespace App\Services\Type\Input;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;

class StateInputType extends InputObjectType implements InputType
{
    /**
     * StateInputType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => 'StateInput',
            'description' => 'State input for mutations args',
            'fields' => function () {
                return [
                    'label' => Type::string(),
                    'weight' => Type::int(),
                    'team' => Type::id(),
                ];
            },
        ];
        parent::__construct($config);
    }
}
