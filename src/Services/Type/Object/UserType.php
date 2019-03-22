<?php

namespace App\Services\Type\Object;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\UserInputType;
use App\Services\Type\Registry\TypesRegistry;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UserType extends FieldResolver implements GraphCustomTypeInterface
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
     * @param TypesRegistry $registry
     */
    public function __construct(UserRepository $userRepository, UserInputType $userInputType, TypesRegistry $registry)
    {
        $this->userRepository = $userRepository;
        $this->userInputType = $userInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'User using application',
            'fields' => function () use($registry) {
                return [
                    'id' => Type::id(),
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'password' => Type::string(),
                    'email' => Type::string(),
                    'enabled' => Type::boolean(),
                    'teams' => ['type' => Type::listOf($registry->getTypeByName('Team'))],
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                return $this->resolveField($value, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    public function getTeams($value)
    {
        if (!$value instanceof User) {
            return null;
        }

        return $value->getTeams();
    }

    /**
     * @return EntityRepository
     */
    public function getTypeRepository(): EntityRepository
    {
        return $this->userRepository;
    }

    /**
     * @return string
     */
    public function getBaseTypeName(): string
    {
        return 'User';
    }

    /**
     * @return InputType
     */
    public function getInputType(): InputType
    {
        return $this->userInputType;
    }

    public function hasResourceAccess($args, $context): bool
    {
        return isset($args['id']) ? $args['id'] === $context['authentication']['userId'] : true;
    }
}
