<?php

namespace App\Services\Type;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * QueryType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $config = [
            'name' => 'Query',
            'fields' => [
                'user' => [
                    'type' => new UserType(),
                    'description' => 'Returns user with id',
                    'args' => [
                        'id' => Type::nonNull(Type::id()),
                    ]
                ],
            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $this->{$info->fieldName}($val, $args, $context, $info);
            }
        ];

        parent::__construct($config);
    }

    public function user($rootValue, $args)
    {
        return $this->em->getRepository(User::class)->find($args['id']);
    }
}
