<?php

namespace App\Services;

use App\Entity\User;
use App\Exception\InvalidUserException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AuthenticationService
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->encoder = $encoder;
        $this->em = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @param ParameterBag $request
     * @throws InvalidUserException
     */
    public function createUserAuthentication(ParameterBag $request)
    {
        $user = new User();
        $user->setFirstName($request->get('firstName') ?? NULL);
        $user->setLastName($request->get('lastName') ?? NULL);
        $user->setEmail($request->get('email') ?? NULL);
        $user->setPassword($request->get('password') ?? NULL);
        $user->setEnabled(false);

        $validationsError = $this->validator->validate($user);

        if ($validationsError->count() > 0) {
            $messages = [];

            foreach ($validationsError as $validation) {
                $messages[$validation->getPropertyPath()] = $validation->getMessage();
            }

            throw new InvalidUserException(json_encode($messages), 422);
        }

        $user->setPassword($this->encoder->encodePassword($user, $request->get('password')));

        $this->em->persist($user);
        $this->em->flush();
    }
}
