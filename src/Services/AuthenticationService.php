<?php

namespace App\Services;

use App\Entity\User;
use App\Exception\InvalidUserException;
use App\Services\Transport\Consumer\MailConsumer;
use App\Services\Transport\Sender\MailSender;
use App\Services\Transport\Transporter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
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

    /**
     * @var TokenBuilder
     */
    private $tokenGenerator;

    /**
     * @var MailConsumer
     */
    private $mailConsumer;

    public function __construct(
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator,
        TokenBuilder $tokenBuilder,
        MailConsumer $mailConsumer)
    {
        $this->encoder = $encoder;
        $this->em = $entityManager;
        $this->validator = $validator;
        $this->tokenGenerator = $tokenBuilder;
        $this->mailConsumer = $mailConsumer;
    }

    /**
     * @param ParameterBag $request
     * @throws InvalidUserException
     * @throws \App\Exception\EmailNotSent
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function createUserAuthentication(ParameterBag $request)
    {
        $user = new User();
        $user->setFirstName($request->get('firstName') ?? NULL);
        $user->setLastName($request->get('lastName') ?? NULL);
        $user->setEmail($request->get('email') ?? NULL);
        $user->setPassword($request->get('password') ?? NULL);
        $user->setEnabled(false);

        $user->setConfirmationToken($this->tokenGenerator->generateUniqToken());

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

        $this->sendUserConfirmationEmail($user);
    }

    /**
     * @param User $user
     * @throws \App\Exception\EmailNotSent
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendUserConfirmationEmail(User $user)
    {
        $sender = new MailSender($user->getFirstName(), $user->getLastName(), $user->getEmail());
        $mailSubject = sprintf('[Gaia] %s, votre confirmation d\'inscription', $user->getFirstName());
        $message = [
            'template' => 'emails/user-confirmation.html.twig',
            'properties' => [
                'user' => $user
            ],
        ];
        $transporter = new Transporter($sender, $mailSubject, $message);

        $this->mailConsumer->emit($transporter);
    }
}
