<?php

namespace App\Services;

class TokenGenerator
{
    public function generateUniqToken()
    {
        return md5(uniqid(rand(), true));
    }
}
