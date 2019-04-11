<?php

namespace App\Normalizer;

use Doctrine\Common\Annotations\AnnotationException;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class GroupSerializer
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * GroupSerializer constructor.
     * @throws AnnotationException
     */
    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $this->normalizer = new ObjectNormalizer($classMetadataFactory);
        $this->serializer = new Serializer([$this->normalizer], [new JsonEncoder()]);
    }

    /**
     * @param $data
     * @param $type
     * @param array|null $groups
     * @return bool|float|int|string
     */
    public function serializeWithGroups($data, $type, array $groups = null)
    {
        return $this->serializer->serialize($data, $type, ['groups' => $groups]);
    }
}
