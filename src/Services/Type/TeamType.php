<?php

namespace App\Services\Type;

use App\Repository\TeamRepository;
use App\Repository\UserRepository;
use App\Services\Type\Input\TeamInputType;
use App\Services\Type\Input\UserInputType;
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
     */
    public function __construct(TeamRepository $teamRepository, TeamInputType $teamInputType)
    {
        $this->teamRepository = $teamRepository;
        $this->teamInputType = $teamInputType;

        $config = [
            'name' => 'Team',
            'description' => 'Team attached to users',
            'fields' => function () {
                return [
                    'id' => Type::id(),
                    'label' => Type::string(),
                    'description' => Type::string(),
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

    public function hasResourceAccess($args, $context): bool
    {
        return true;
    }
}
