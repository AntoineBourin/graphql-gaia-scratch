<?php

namespace App\Services\Transport\Consumer;

use App\Exception\EmailNotSent;
use App\Services\Transport\Transporter;

class MailConsumer implements ConsumerInterface
{
    /**
     * @var \Twig_Environment
     */
    private $templating;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Twig_Environment $templating, \Swift_Mailer $mailer)
    {
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    /**
     * @param Transporter $transporter
     * @return bool
     * @throws EmailNotSent
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function emit(Transporter $transporter): void
    {
        $body = $this->templating->render(
            $transporter->message['template'],
            $transporter->message['properties']
        );

        $message = (new \Swift_Message($transporter->subject))
            ->setFrom($transporter->from)
            ->setTo($transporter->sender->getEmail())
            ->setBody($body, 'text/html');

        $successfulRecipients = $this->mailer->send($message);

        if ($successfulRecipients === 0) {
            throw new EmailNotSent('Message cannot be sent to email.', 500);
        }
    }
}
