<?php

namespace App\Services\Transport;

use App\Services\Transport\Sender\SenderInterface;

class Transporter
{
    /**
     * @var SenderInterface
     */
    public $sender;

    /**
     * @var string
     */
    public $from;

    /**
     * @var string
     */
    public $subject;

    /**
     * @var array
     */
    public $message;

    public function __construct(SenderInterface $sender, string $subject, array $message, string $from = null)
    {
        $this->sender = $sender;
        $this->subject = $subject;
        $this->message = $message;
        $this->from = $from ?? getenv('APP_MAIL');
    }
}
