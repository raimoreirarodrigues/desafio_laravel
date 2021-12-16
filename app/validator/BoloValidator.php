<?php

namespace App\Validators;

use App\Validators\BaseValidator;


class BoloValidator extends BaseValidator
{
    public function getRules(array $data = []): array
    {
        $validationRules = [
            'nome'       =>['required'],
            'peso'       =>['required','numeric','between:0,999999.99'],
            'valor'      =>['required','numeric','between:0,999999999.99'],
            'quantidade' =>['nullable','integer'],
            'emails'     =>['nullable','array'],
        ];

        return $validationRules;
    }

    public function getMessages(): array
    {
        return [];
    }
}
