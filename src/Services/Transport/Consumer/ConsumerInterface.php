<?php

namespace App\Services\Transport\Consumer;

use App\Services\Transport\Transporter;

interface ConsumerInterface
{
    public function emit(Transporter $transporter): void;
}
