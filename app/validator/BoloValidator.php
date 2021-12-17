<?php

namespace App\Validators;

use App\Validators\BaseValidator;


class BoloValidator extends BaseValidator
{
    public function getRules(array $data = [],$id=null): array
    {
        $validationRules = [
            'nome'          =>['required','unique:bolos,nome,'.$id],
            'peso'          =>['required','numeric','between:0,999999.99'],
            'valor'         =>['required','numeric','between:0,999999999.99'],
            'quantidade'    =>['nullable','integer'],
            'interessados'  =>['nullable','array'],
        ];

        return $validationRules;
    }

    public function getMessages(): array
    {
        return [];
    }
}
