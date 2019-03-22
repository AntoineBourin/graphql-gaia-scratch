<?php

namespace App\Services\Type\Object;

use App\Entity\Team;
use App\Repository\TeamRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\TeamInputType;
use App\Services\Type\Registry\TypesRegistry;
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
     */
    public function __construct(TeamRepository $teamRepository, TeamInputType $teamInputType, TypesRegistry $registry)
    {
        $this->teamRepository = $teamRepository;
        $this->teamInputType = $teamInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'Team attached to users',
            'fields' => function () use($registry) {
                return [
                    'id' => Type::id(),
                    'label' => Type::string(),
                    'description' => Type::string(),
                    'users' => ['type' => Type::listOf($registry->getTypeByName('user'))],
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                return $this->resolveField($value, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    /**
     * @param $value
     * @return \App\Entity\User[]|\Doctrine\Common\Collections\Collection|null
     */
    public function methodUsers($value)
    {
        if (!$value instanceof Team) {
            return null;
        }

        foreach ($value->getUsers() as $user) {
            $user->removeTeam($value);
        }

        return $value->getUsers();
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
