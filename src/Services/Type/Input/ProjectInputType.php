<?php

namespace App\Services\Type\Input;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;

class ProjectInputType extends InputObjectType implements InputType
{
    /**
     * ProjectInputType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => 'ProjectInput',
            'description' => 'Project input for mutations args',
            'fields' => function () {
                return [
                    'label' => Type::string(),
                    'states' => Type::listOf(Type::id()),
                    'team' => Type::id(),
                ];
            },
        ];
        parent::__construct($config);
    }
}
