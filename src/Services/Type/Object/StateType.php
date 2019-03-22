<?php

namespace App\Services\Type\Object;

use App\Entity\State;
use App\Entity\Team;
use App\Repository\StateRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\StateInputType;
use App\Services\Type\Registry\TypesRegistry;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class StateType extends FieldResolver implements GraphCustomTypeInterface
{
    /**
     * @var StateRepository
     */
    private $stateRepository;

    /**
     * @var StateInputType
     */
    private $stateInputType;

    /**
     * @param StateRepository $stateRepository
     * @param StateInputType $stateInputType
     * @param TypesRegistry $registry
     */
    public function __construct(StateRepository $stateRepository, StateInputType $stateInputType, TypesRegistry $registry)
    {
        $this->stateRepository = $stateRepository;
        $this->stateInputType = $stateInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'Different state inside a team',
            'fields' => function () use($registry) {
                return [
                    'id' => Type::id(),
                    'label' => Type::string(),
                    'weight' => Type::int(),
                    'team' => ['type' => $registry->getTypeByName('team')],
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
     * @return \App\Entity\Team
     */
    public function getTeam($value): Team
    {
        if (!$value instanceof State) {
            return null;
        }

        return $value->getTeam();
    }

    /**
     * @return EntityRepository
     */
    public function getTypeRepository(): EntityRepository
    {
        return $this->stateRepository;
    }

    /**
     * @return string
     */
    public function getBaseTypeName(): string
    {
        return 'state';
    }

    /**
     * @return InputType
     */
    public function getInputType(): InputType
    {
        return $this->stateInputType;
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
