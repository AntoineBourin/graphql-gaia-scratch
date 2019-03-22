<?php

namespace App\Services;

use App\Services\Type\Registry\TypesRegistry;
use App\Services\Type\Root\MutationType;
use App\Services\Type\Root\QueryType;
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
     * SchemaBuilder constructor.
     * @param EntityManagerInterface $em
     * @param QueryType $queryType
     * @param MutationType $mutationType
     */
    public function __construct(
        EntityManagerInterface $em,
        QueryType $queryType,
        MutationType $mutationType)
    {
        $this->schema = new Schema([
            'query' => $queryType,
            'mutation' => $mutationType,
        ]);
        $this->em = $em;
    }

    /**
     * @param string|null $query
     * @param array $context
     * @return JsonResponse
     */
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
