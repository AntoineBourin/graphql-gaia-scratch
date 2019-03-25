<?php

namespace App\Services\Type\Mapper;

use App\Services\Type\Registry\TypesRegistry;

class GraphTypeMapper
{
    /**
     * @var TypesRegistry
     */
    private $typesRegistry;

    /**
     * @var MutationTypeBuilder
     */
    private $mutationTypeBuilder;

    /**
     * @var QueryTypeBuilder
     */
    private $queryTypeBuilder;

    /**
     * GraphTypeMapper constructor.
     * @param TypesRegistry $typesRegistry
     * @param QueryTypeBuilder $queryTypeBuilder
     * @param MutationTypeBuilder $mutationTypeBuilder
     */
    public function __construct(TypesRegistry $typesRegistry, QueryTypeBuilder $queryTypeBuilder, MutationTypeBuilder $mutationTypeBuilder)
    {
        $this->typesRegistry = $typesRegistry;
        $this->queryTypeBuilder = $queryTypeBuilder;
        $this->mutationTypeBuilder = $mutationTypeBuilder;
    }

    /**
     * @return array
     */
    public function getBasicsQueries(): array
    {
        $queriesFields = [];
        $builder = $this->queryTypeBuilder;
        foreach ($this->typesRegistry->getTypes() as $type) {
            $queriesFields = array_merge(
                $queriesFields,
                array_merge($builder($type->getTypeRepository(), $type->getBaseTypeName(), $type), $type->getCustomQueries())
            );
        }

        return $queriesFields;
    }

    /**
     * @return array
     */
    public function getBasicsMutations(): array
    {
        $mutationsFields = [];
        $builder = $this->mutationTypeBuilder;
        foreach ($this->typesRegistry->getTypes() as $type) {
            $mutationsFields = array_merge(
                $mutationsFields,
                array_merge($builder($type->getTypeRepository(), $type->getBaseTypeName(), $type, $type->getInputType()), $type->getCustomMutations())
            );
        }

        return $mutationsFields;
    }
}
