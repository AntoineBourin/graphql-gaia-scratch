<?php

namespace App\Services\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class UserType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'User',
            'description' => 'User using application',
            'fields' => function () {
                return [
                    'id' => Type::id(),
                    'firstName' => Type::string(),
                    'lastName' => Type::string(),
                    'password' => Type::string(),
                    'email' => Type::string(),
                    'enabled' => Type::boolean(),
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                $resolverMethod = sprintf('method%s', ucfirst($info->fieldName));
                if (method_exists($this, $resolverMethod)) {
                    return $this->{$resolverMethod}($value, $args, $context);
                } else {
                    $methodField = sprintf('get%s', ucfirst($info->fieldName));
                    if (method_exists($value, $methodField)) {
                        return $value->{$methodField}();
                    } else {
                        return null;
                    }
                }
            }
        ];

        parent::__construct($config);
    }


}
