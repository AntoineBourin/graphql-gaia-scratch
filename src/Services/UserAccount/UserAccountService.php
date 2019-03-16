<?php

namespace App\Services\UserAccount;

use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserAccountService
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $repository;
        $this->em = $entityManager;
    }

    public function activateUserWithConfirmToken(string $token)
    {
        $user = $this->userRepository->findBy(['confirmationToken' => $token], null, 1);

        if (!$user || !isset($user[0])) {
            throw new UserNotFoundException('No user exist with this confirmation token');
        }

        $user[0]->setEnabled(true);
        $user[0]->setConfirmationToken('');

        $this->em->persist($user[0]);
        $this->em->flush();
    }
}
