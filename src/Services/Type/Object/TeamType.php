<?php

namespace App\Services\Type\Object;

use App\Repository\TeamRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\TeamInputType;
use App\Services\Type\Registry\TypesRegistry;
use App\Services\Type\Scalar\DateTimeType;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class TeamType extends FieldResolver implements GraphCustomTypeInterface
{
    /**
     * @var TeamRepository
     */
    private $teamRepository;

    /**
     * @var TeamInputType
     */
    private $teamInputType;

    /**
     * @param TeamRepository $teamRepository
     * @param TeamInputType $teamInputType
     * @param TypesRegistry $registry
     * @param DateTimeType $dateTimeType
     */
    public function __construct(TeamRepository $teamRepository, TeamInputType $teamInputType, TypesRegistry $registry, DateTimeType $dateTimeType)
    {
        $this->teamRepository = $teamRepository;
        $this->teamInputType = $teamInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'Team attached to users',
            'fields' => function () use($registry, $dateTimeType) {
                return [
                    'id' => Type::id(),
                    'label' => Type::string(),
                    'description' => Type::string(),
                    'users' => ['type' => Type::listOf($registry->getTypeByName('user'))],
                    'projects' => ['type' => Type::listOf($registry->getTypeByName('project'))],
                    'createdAt' => $dateTimeType,
                    'updatedAt' => $dateTimeType,
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                return $this->resolveField($value, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    /**
     * @return EntityRepository
     */
    public function getTypeRepository(): EntityRepository
    {
        return $this->teamRepository;
    }

    /**
     * @return string
     */
    public function getBaseTypeName(): string
    {
        return 'team';
    }

    /**
     * @return InputType
     */
    public function getInputType(): InputType
    {
        return $this->teamInputType;
    }

    /**
     * @param $args
     * @param $context
     * @return bool
     */
    public function hasResourceAccess($args, $context): bool
    {
        return true;
    }
}
