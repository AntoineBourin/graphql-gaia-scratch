<?php

namespace App\Services\Type\Input;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\Type;

class IssueInputType extends InputObjectType implements InputType
{
    /**
     * StateInputType constructor.
     */
    public function __construct()
    {
        $config = [
            'name' => 'IssueInput',
            'description' => 'Issue input for mutations args',
            'fields' => function () {
                return [
                    'title' => Type::string(),
                    'description' => Type::string(),
                    'createdBy' => Type::id(),
                    'state' => Type::id(),
                    'assignedTo' => Type::id(),
                ];
            },
        ];
        parent::__construct($config);
    }
}
