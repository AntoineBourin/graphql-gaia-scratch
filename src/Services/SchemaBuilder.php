<?php

namespace App\Services;

use App\Services\Type\MutationType;
use App\Services\Type\QueryType;
use App\Services\Type\Registry\TypesRegistry;
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

    public function __construct(
        EntityManagerInterface $em,
        QueryType $queryType,
        MutationType $mutationType,
        TypesMapper $typesMapper,
        TypesRegistry $typesRegistry)
    {
        $this->typesMapper = $typesMapper;
        $this->schema = new Schema([
            'query' => $queryType,
            'mutation' => $mutationType,
            'typeLoader' => function($name) use ($typesRegistry) {
                return $typesRegistry->getTypeByName($name);
            }
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
