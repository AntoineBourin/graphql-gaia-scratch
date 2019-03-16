<?php

namespace App\Services;

class TokenBuilder
{
    public function generateUniqToken()
    {
        return md5(uniqid(rand(), true));
    }
}
