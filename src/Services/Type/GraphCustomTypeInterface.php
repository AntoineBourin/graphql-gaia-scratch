<?php

namespace App\Services\Type;

interface GraphCustomTypeInterface
{
    public function getRootQuery(): array;
    public function getRootMutations(): array;
}
