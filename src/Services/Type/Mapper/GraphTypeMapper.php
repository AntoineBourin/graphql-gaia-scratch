<?php

namespace App\Services\Type\Mapper;

use App\Services\Type\TypesChain;

class GraphTypeMapper
{
    /**
     * @var TypesChain
     */
    private $typesChain;

    /**
     * @var MutationTypeBuilder
     */
    private $mutationTypeBuilder;

    /**
     * @var QueryTypeBuilder
     */
    private $queryTypeBuilder;

    public function __construct(TypesChain $typesChain, QueryTypeBuilder $queryTypeBuilder, MutationTypeBuilder $mutationTypeBuilder)
    {
        $this->typesChain = $typesChain;
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
        foreach ($this->typesChain->getTypes() as $type) {
            $queriesFields = array_merge($queriesFields, $builder($type->getTypeRepository(), $type->getBaseTypeName(), $type));
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
        foreach ($this->typesChain->getTypes() as $type) {
            $mutationsFields = array_merge($mutationsFields, $builder($type->getTypeRepository(), $type->getBaseTypeName(), $type, $type->getInputType()));
        }

        return $mutationsFields;
    }
}
