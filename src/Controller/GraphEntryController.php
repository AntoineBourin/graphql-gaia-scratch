<?php

namespace App\Controller;

use App\Services\ContextBuilder;
use App\Services\SchemaBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class GraphEntryController
{
    /**
     * @var SchemaBuilder
     */
    private $schemaBuilder;

    /**
     * @var ContextBuilder
     */
    private $contextBuilder;

    /**
     * GraphEntryController constructor.
     * @param SchemaBuilder $schemaBuilder
     * @param ContextBuilder $contextBuilder
     */
    public function __construct(SchemaBuilder $schemaBuilder, ContextBuilder $contextBuilder)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->contextBuilder = $contextBuilder;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function graphQLEntryPoint(Request $request)
    {
        $requestBody = json_decode($request->getContent());
        $currentContext = $this->contextBuilder->generate();

        return $this->schemaBuilder->triggerNewGraphQuery($requestBody->query ?? NULL, $currentContext, isset($requestBody->variables) ? (array) $requestBody->variables : NULL);
    }
}
