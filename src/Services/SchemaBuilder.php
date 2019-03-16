<?php

namespace App\Services;

use App\Services\Type\QueryType;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Symfony\Component\HttpFoundation\JsonResponse;

class SchemaBuilder
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Schema
     */
    private $schema;

    /**
     * @var TypesMapper
     */
    private $typesMapper;

    public function __construct(EntityManagerInterface $em, QueryType $queryType, TypesMapper $typesMapper)
    {
        $this->typesMapper = $typesMapper;
        $this->schema = new Schema([
            'query' => $queryType,
        ]);
        $this->em = $em;
    }

    public function throwNewGraphQuery(?string $query, array $context): JsonResponse
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            $query,
            null,
            $context
        );

        return new JsonResponse($result->toArray(Debug::INCLUDE_DEBUG_MESSAGE));
    }
}
