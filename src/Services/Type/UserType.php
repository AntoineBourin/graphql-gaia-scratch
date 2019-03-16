<?php

namespace App\Services\Type;

use App\Repository\UserRepository;
use App\Services\Type\Input\UserInputType;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UserType extends CustomTypeBuilder implements GraphCustomTypeInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var UserInputType
     */
    private $userInputType;

    /**
     * @param UserRepository $userRepository
     * @param UserInputType $userInputType
     */
    public function __construct(UserRepository $userRepository, UserInputType $userInputType)
    {
        $this->userRepository = $userRepository;
        $this->userInputType = $userInputType;
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

    public function getTypeRepository(): EntityRepository
    {
        return $this->userRepository;
    }

    public function getBaseTypeName(): string
    {
        return 'user';
    }

    public function getInputType(): InputType
    {
        return $this->userInputType;
    }
}
