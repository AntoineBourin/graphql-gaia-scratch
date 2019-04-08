<?php

namespace App\Services\Type\Object;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\UserInputType;
use App\Services\Type\Registry\TypesRegistry;
use App\Services\Type\Scalar\EmailType;
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
     * @param EmailType $emailType
     */
    public function __construct(UserRepository $userRepository, UserInputType $userInputType, TypesRegistry $registry, EmailType $emailType)
    {
        $this->userRepository = $userRepository;
        $this->userInputType = $userInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'User using application',
            'fields' => function () use($registry, $emailType) {
                return [
                    'id' => Type::id(),
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'email' => $emailType,
                    'enabled' => Type::boolean(),
                    'teams' => ['type' => Type::listOf($registry->getTypeByName('team'))],
                    'assignedIssues' => ['type' => Type::listOf($registry->getTypeByName('issue'))],
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                return $this->resolveField($value, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function getCustomQueries(): array
    {
        return [
            'me' => [
                'type' => $this,
                'description' => 'Get data of current user',
                'resolve' => function($value, $args, $context, ResolveInfo $info) {
                    return $context['authentication'] ?? NULL;
                }
            ],
        ];
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
        return 'user';
    }

    /**
     * @return InputType
     */
    public function getInputType(): InputType
    {
        return $this->userInputType;
    }

    /**
     * @param $args
     * @param $context
     * @return bool
     */
    public function hasResourceAccess($args, $context): bool
    {
        return isset($args['id']) ? $args['id'] === $context['authentication']['userId'] : true;
    }
}
