<?php

namespace App\Services\Type;

use App\Repository\UserRepository;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UserType extends CustomTypeBuilder implements GraphCustomTypeInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

        parent::__construct($config, $userRepository, 'user');
    }


    public function getRootQuery(): array
    {
        $currentInstance = new static($this->userRepository);

        return $this->getBaseTypeQueries($currentInstance);
    }

    public function getRootMutations(): array
    {
        return [];
    }
}
