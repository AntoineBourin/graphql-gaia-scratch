<?php

namespace App\Services\Type;

use App\Repository\UserRepository;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserType extends CustomTypeBuilder implements GraphCustomTypeInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param UserRepository $userRepository
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(UserRepository $userRepository, TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
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

    /**
     * @return array
     */
    public function getRootQuery(): array
    {
        return array_merge($this->getBaseTypeQueries($this));
    }

    /**
     * @return array
     */
    public function getRootMutations(): array
    {
        return [];
    }
}
