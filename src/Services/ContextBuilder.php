<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Request;

class ContextBuilder
{
    public function generate(Request $request): array
    {
        $context = [];

        // TODO:: Add custom context in requests

        return $context;
    }
}
