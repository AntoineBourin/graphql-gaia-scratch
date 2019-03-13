<?php

namespace App\Services;

use App\Services\Type\QueryType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Debug;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SchemaBuilder
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    private $schema;

    public function __construct(EntityManagerInterface $em)
    {
        $this->schema = new Schema([
            'query' => new QueryType($em)
        ]);
        $this->em = $em;
    }

    public function throwNewGraphQuery(?string $query)
    {
        $result = GraphQL::executeQuery(
            $this->schema,
            $query,
            null,
            null
        );

        return new JsonResponse($result->toArray(Debug::INCLUDE_DEBUG_MESSAGE));
    }
}
