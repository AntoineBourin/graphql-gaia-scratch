<?php

namespace App\Services\Type\Object;

use App\Repository\ProjectRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\ProjectInputType;
use App\Services\Type\Registry\TypesRegistry;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class ProjectType extends FieldResolver implements GraphCustomTypeInterface
{
    /**
     * @var ProjectRepository
     */
    private $projectRepository;

    /**
     * @var ProjectInputType
     */
    private $projectInputType;

    /**
     * @param ProjectRepository $projectRepository
     * @param ProjectInputType $projectInputType
     * @param TypesRegistry $registry
     */
    public function __construct(ProjectRepository $projectRepository, ProjectInputType $projectInputType, TypesRegistry $registry)
    {
        $this->projectRepository = $projectRepository;
        $this->projectInputType = $projectInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'Different projects created by users on teams',
            'fields' => function () use($registry) {
                return [
                    'id' => Type::id(),
                    'label' => Type::string(),
                    'states' => ['type' => Type::listOf($registry->getTypeByName('state'))],
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
     * @return EntityRepository
     */
    public function getTypeRepository(): EntityRepository
    {
        return $this->projectRepository;
    }

    /**
     * @return string
     */
    public function getBaseTypeName(): string
    {
        return 'project';
    }

    /**
     * @return InputType
     */
    public function getInputType(): InputType
    {
        return $this->projectInputType;
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
