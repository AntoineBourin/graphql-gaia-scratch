<?php

namespace App\Services\Type\Access;

use GraphQL\Error\Error;

class AccessChecker
{
    public function hasAccess($args, $context, $type)
    {
        if (!$type->hasResourceAccess($args, $context)) {
            throw Error::createLocatedError('You don\'t have right access to call this GraphQL endpoint.');
        }
    }
}
