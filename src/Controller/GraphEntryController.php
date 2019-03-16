<?php

namespace App\Controller;

use App\Services\ContextBuilder;
use App\Services\SchemaBuilder;
use Doctrine\ORM\EntityManager;
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

    public function __construct(SchemaBuilder $schemaBuilder, ContextBuilder $contextBuilder)
    {
        $this->schemaBuilder = $schemaBuilder;
        $this->contextBuilder = $contextBuilder;
    }

    public function graphQLEntryPoint(Request $request)
    {
        $requestBody = json_decode($request->getContent());
        $currentContext = $this->contextBuilder->generate($request);
        return $this->schemaBuilder->throwNewGraphQuery($requestBody->query ?? NULL, $currentContext);
    }
}
