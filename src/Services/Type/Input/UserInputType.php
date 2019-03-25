<?php

namespace App\Services\Type\Input;

use App\Services\Type\Scalar\EmailType;
use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;

class UserInputType extends InputObjectType implements InputType
{
    /**
     * UserInputType constructor.
     * @param EmailType $emailType
     */
    public function __construct(EmailType $emailType)
    {
        $config = [
            'name' => 'UserInput',
            'description' => 'User input for mutations args',
            'fields' => function () use($emailType) {
                return [
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'email' => $emailType,
                    'enabled' => Type::boolean(),
                    'teams' => Type::listOf(Type::id()),
                ];
            },
        ];
        parent::__construct($config);
    }
}
