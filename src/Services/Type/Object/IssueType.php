<?php

namespace App\Services\Type\Object;

use App\Repository\IssueRepository;
use App\Services\Type\FieldResolver;
use App\Services\Type\GraphCustomTypeInterface;
use App\Services\Type\Input\IssueInputType;
use App\Services\Type\Registry\TypesRegistry;
use App\Services\Type\Scalar\DateTimeType;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class IssueType extends FieldResolver implements GraphCustomTypeInterface
{
    /**
     * @var IssueRepository
     */
    private $issueRepository;

    /**
     * @var IssueInputType
     */
    private $issueInputType;

    /**
     * @param IssueRepository $issueRepository
     * @param IssueInputType $issueInputType
     * @param TypesRegistry $registry
     */
    public function __construct(IssueRepository $issueRepository, IssueInputType $issueInputType, TypesRegistry $registry, DateTimeType $dateTimeType)
    {
        $this->issueRepository = $issueRepository;
        $this->issueInputType = $issueInputType;

        $config = [
            'name' => $this->getBaseTypeName(),
            'description' => 'Different issues created by users assigned to users',
            'fields' => function () use($registry, $dateTimeType) {
                return [
                    'id' => Type::id(),
                    'title' => Type::string(),
                    'description' => Type::string(),
                    'createdBy' => ['type' => $registry->getTypeByName('user')],
                    'state' => ['type' => $registry->getTypeByName('state')],
                    'assignedTo' => ['type' => $registry->getTypeByName('user')],
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
        return $this->issueRepository;
    }

    /**
     * @return string
     */
    public function getBaseTypeName(): string
    {
        return 'issue';
    }

    /**
     * @return InputType
     */
    public function getInputType(): InputType
    {
        return $this->issueInputType;
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
