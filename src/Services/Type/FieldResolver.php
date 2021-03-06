<?php

namespace App\Services\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class FieldResolver extends ObjectType
{
    /**
     * FieldResolver constructor.
     * @param $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }

    /**
     * @param $value
     * @param $args
     * @param $context
     * @param ResolveInfo $info
     * @return string|null
     */
    public function resolveField($value, $args, $context, ResolveInfo $info)
    {
        $resolverMethod = sprintf('method%s', ucfirst($info->fieldName));
        if (method_exists($this, $resolverMethod)) {
            return $this->{$resolverMethod}($value, $args, $context);
        } else {
            $methodField = sprintf('get%s', ucfirst($info->fieldName));
            return method_exists($value, $methodField) ? $value->{$methodField}() : null;
        }
    }

    /**
     * @return array
     */
    public function getCustomQueries(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function getCustomMutations(): array
    {
        return [];
    }
}
