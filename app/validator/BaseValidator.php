<?php 

namespace App\Validators; 

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

abstract class BaseValidator 
{

    private $validator;

    public abstract function getRules(array $data = []) : array;
    public abstract function getMessages() : array;

    public function getValidator(array $data = [])
    {
        return $this->validator;
    }

    public function fails(array $data = []) 
    {
        $this->setValidator($data);
        return $this->getValidator()->fails();
    }

    public function validated(array $data = [])
    {
        if ($this->fails($data)) {
            throw new ValidationException($this->getValidator());
        }
    }

    private function setValidator(array $data = [])
    {
        $this->validator =  Validator::make($data, $this->getRules(), $this->getMessages());
    }
}