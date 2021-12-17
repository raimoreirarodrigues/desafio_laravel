<?php

namespace App\Validators;

use App\Validators\BaseValidator;


class BoloInteressadoValidator extends BaseValidator
{
    public function getRules(array $data = [],$id=null): array
    {
        $validationRules = [
            'interessados'  =>['required','array'],
        ];

        return $validationRules;
    }

    public function getMessages(): array
    {
        return [];
    }
}
