<?php

namespace App\Services\Type\Input;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;

class TeamInputType extends InputObjectType implements InputType
{
    public function __construct()
    {
        $config = [
            'name' => 'TeamInput',
            'description' => 'Team input for mutations args',
            'fields' => function () {
                return [
                    'id' => Type::int(),
                    'label' => Type::string(),
                    'description' => Type::string(),
                ];
            },
        ];
        parent::__construct($config);
    }
}
