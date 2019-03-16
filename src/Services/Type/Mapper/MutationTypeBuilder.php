<?php

namespace App\Services\Type\Mapper;

use App\Services\Persister\DataPersister;
use App\Services\Type\GraphCustomTypeInterface;
use Doctrine\ORM\EntityRepository;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;

class MutationTypeBuilder
{
    public function __construct(DataPersister $dataPersister)
    {

    }

    public function __invoke(EntityRepository $typeRepository, string $baseName, GraphCustomTypeInterface $type, InputType $inputType): array
    {
//        $creationEndpoint = sprintf('create%s', ucfirst($baseName));
//        $updateEndpoint = sprintf('update%s', ucfirst($baseName));
//        return [
//            $updateEndpoint => [
//                'type' => $type,
//                'description' => sprintf('Update %s with input', $baseName),
//                'args' => [
//                    'input' => $inputType,
//                ],
//                'resolve' => function($value, $args, $context, ResolveInfo $info) {
//                    return $this->typeRepository->find($args['id']);
//                }
//            ],
//        ];

        // TODO:: Denormalize object
        // TODO:: Persist object with DataPersister


        return [];
    }
}
