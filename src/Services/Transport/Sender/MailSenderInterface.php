<?php

namespace App\Services\Transport\Sender;

interface MailSenderInterface extends SenderInterface
{
    public function getFirstName(): string;

    public function getLastName(): string;

    public function getEmail(): string;
}
