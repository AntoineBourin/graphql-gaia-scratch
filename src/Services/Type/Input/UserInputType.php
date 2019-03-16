<?php

namespace App\Services\Type\Input;

use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

class UserInputType extends ObjectType implements InputType
{
    public function __construct()
    {
        $config = [
            'name' => 'UserInput',
            'description' => 'User input for mutations args',
            'fields' => function () {
                return [
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'password' => Type::string(),
                    'email' => Type::string(),
                    'enabled' => Type::boolean(),
                ];
            },
        ];
        parent::__construct($config);
    }
}
