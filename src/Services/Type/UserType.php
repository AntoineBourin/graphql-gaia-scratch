<?php

namespace App\Services\Type;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UserType extends FieldResolver implements GraphCustomTypeInterface
{
    public function __construct()
    {
        $config = [
            'name' => 'User',
            'description' => 'User using application',
            'fields' => function () {
                return [
                    'id' => Type::id(),
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'password' => Type::string(),
                    'email' => Type::string(),
                    'enabled' => Type::boolean(),
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                return $this->resolveField($value, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }


    public function getRootQuery(): array
    {
        $currentInstance = new static();
        return [
            'getUserById' => [
                'type' => $currentInstance,
                'description' => 'Returns user with id',
                'args' => [
                    'id' => Type::nonNull(Type::id()),
                ],
            ],
            'getUsers' => [
                'type' => Type::listOf($currentInstance),
                'description' => 'Return all users',
                'args' => [],
            ],
        ];
    }

    public function getRootMutations(): array
    {
        return [];
    }
}
