<?php

namespace App\Controller;

use App\Services\SchemaBuilder;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

class GraphEntryController
{
    private $schemaBuilder;

    public function __construct(SchemaBuilder $schemaBuilder)
    {
        $this->schemaBuilder = $schemaBuilder;
    }

    public function graphQLEntryPoint(Request $request)
    {
        $requestBody = json_decode($request->getContent());
        return $this->schemaBuilder->throwNewGraphQuery($requestBody->query);
    }
}
