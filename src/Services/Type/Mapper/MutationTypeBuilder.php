<?php

namespace App\Services\Type\Mapper;

use App\Services\Persister\DataPersister;
use App\Services\Type\GraphCustomTypeInterface;
use Doctrine\ORM\EntityRepository;
use GraphQL\Error\Error;
use GraphQL\Type\Definition\InputType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class MutationTypeBuilder
{
    /**
     * @var DataPersister
     */
    private $dataPersister;

    public function __construct(DataPersister $dataPersister)
    {
        $this->dataPersister = $dataPersister;
    }

    public function __invoke(EntityRepository $typeRepository, string $baseName, GraphCustomTypeInterface $type, InputType $inputType): array
    {
        $updateEndpoint = sprintf('update%s', ucfirst($baseName));
        $deleteEndpoint = sprintf('delete%s', ucfirst($baseName));
        $creationEndpoint = sprintf('create%s', ucfirst($baseName));
        return [
            $updateEndpoint => [
                'type' => $type,
                'description' => sprintf('Update %s with input', $baseName),
                'args' => [
                    'input' => $inputType,
                    'id' => Type::id(),
                ],
                'resolve' => function($value, $args, $context, ResolveInfo $info) use ($typeRepository, $baseName) {
                    $objectClass = $typeRepository->find($args['id']);

                    if (!$objectClass) {
                        throw Error::createLocatedError(sprintf('%s doesn\'t exist.', $baseName));
                    }

                    $context = ['resource_class' => get_class($objectClass), 'object_to_populate' => $objectClass];
                    $denormalizedObject = $this->dataPersister->denormalizeObject($args['input'], get_class($objectClass), $context);

                    return $this->dataPersister->persist($denormalizedObject);
                },
            ],
            $deleteEndpoint => [
                'type' => $type,
                'description' => sprintf('Delete %s with ID', $baseName),
                'args' => [
                    'id' => Type::id(),
                ],
                'resolve' => function($value, $args, $context, ResolveInfo $info) use ($typeRepository, $baseName) {
                    $objectClass = $typeRepository->find($args['id']);

                    if (!$objectClass) {
                        throw Error::createLocatedError(sprintf('%s doesn\'t exist.', $baseName));
                    }

                    return $this->dataPersister->remove($objectClass);
                },
            ],
            $creationEndpoint => [
                'type' => $type,
                'description' => sprintf('Create %s with input', $baseName),
                'args' => [
                    'input' => $inputType,
                ],
                'resolve' => function($value, $args, $context, ResolveInfo $info) use ($typeRepository, $baseName) {
                    $resourceClass = $typeRepository->getClassName();
                    $denormalizedObject = $this->dataPersister->denormalizeObject($args['input'], $resourceClass, []);

                    return $this->dataPersister->persist($denormalizedObject);
                },
            ],
        ];
    }
}