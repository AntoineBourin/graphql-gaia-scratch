<?php

namespace App\Services\Persister;



use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use GraphQL\Error\Error;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DataPersister
{
    /**
     * @var ManagerRegistry
     */
    private $managerRegistry;

    /**
     * @var DenormalizerInterface
     */
    private $denormalizer;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(ManagerRegistry $managerRegistry, DenormalizerInterface $denormalizer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $this->managerRegistry = $managerRegistry;
        $this->denormalizer = $denormalizer;
        $this->validator = $validator;
        $this->em = $entityManager;
    }

    /**
     * @param $data
     * @return \Doctrine\Common\Persistence\ObjectManager|null
     */
    public function getManager($data)
    {
        return \is_object($data) ? $this->managerRegistry->getManagerForClass(get_class($data)) : null;
    }

    /**
     * @param $data
     * @return bool
     */
    public function supports($data): bool
    {
        return null !== $this->getManager($data);
    }

    /**
     * @param $data
     * @param $class
     * @param array $context
     * @return object
     * @throws \Symfony\Component\Serializer\Exception\ExceptionInterface
     */
    public function denormalizeObject($data, $class, array $context)
    {
        return $this->denormalizer->denormalize($data, $class, 'graphql', $context);
    }

    /**
     * @param $data
     * @return mixed
     * @throws Error
     */
    public function persist($data)
    {
        if (!$this->getManager($data)) {
            return $data;
        }

        $this->validate($data);

        $manager = $this->getManager($data);

        if (!$manager->contains($data))  {
            $manager->persist($data);
        }

        $manager->flush();
        $manager->refresh($data);

        return $data;
    }

    /**
     * @param $data
     * @return null
     */
    public function remove($data)
    {
        if (!$this->getManager($data)) {
            return $data;
        }

        $manager = $this->getManager($data);
        $manager->remove($data);
        $manager->flush();

        return null;
    }

    /**
     * @param $data
     * @throws Error
     */
    public function validate($data)
    {
        $errors = $this->validator->validate($data);
        if (count($errors) === 0) {
            return;
        }

        foreach ($errors as $error) {
            throw Error::createLocatedError($error->getMessage(), null, $error->getPropertyPath());
        }
    }
}
