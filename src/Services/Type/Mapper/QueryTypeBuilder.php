<?php

namespace App\Services\Type\Mapper;

use App\Services\Type\Access\AccessChecker;
use App\Services\Type\GraphCustomTypeInterface;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryTypeBuilder
{
    /**
     * @var AccessChecker
     */
    private $accessChecker;

    /**
     * QueryTypeBuilder constructor.
     * @param AccessChecker $accessChecker
     */
    public function __construct(AccessChecker $accessChecker)
    {
        $this->accessChecker = $accessChecker;
    }

    /**
     * @param EntityRepository $typeRepository
     * @param string $baseName
     * @param GraphCustomTypeInterface $type
     * @return array
     */
    public function __invoke(EntityRepository $typeRepository, string $baseName, GraphCustomTypeInterface $type): array
    {
        return [
            $baseName => [
                'type' => $type,
                'description' => sprintf('Returns %s with id', $baseName),
                'args' => [
                    'id' => Type::nonNull(Type::id()),
                ],
                'resolve' => function($value, $args, $context, ResolveInfo $info) use ($typeRepository, $type) {
                    $this->accessChecker->hasAccess($args, $context, $type);
                    return $typeRepository->find($args['id']);
                }
            ],
            sprintf('%ss', $baseName) => [
                'type' => Type::listOf($type),
                'description' => sprintf('Return all %ss', $baseName),
                'args' => [],
                'resolve' => function($value, $args, $context, ResolveInfo $info) use ($typeRepository, $type) {
                    $this->accessChecker->hasAccess($args, $context, $type);
                    return $typeRepository->findBy([], null, $value['limit'] ?? NULL, $value['offset'] ?? NULL);
                },
            ],
        ];
    }
}
