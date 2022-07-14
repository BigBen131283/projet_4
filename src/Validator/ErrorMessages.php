<?php

namespace App\Validator;

class ErrorMessages
{
    private $errorMessages = [];

    public function addError($attribute, $message)
    {
        $errorMessages[$attribute] = $message;
    }

    public function getFirstError($attribute)
    {
        if(isset($this->errorMessages["$attribute"][0]))
        {
            return $this->errorMessages["$attribute"][0];
        }
    }

    public function hasError()
    {
        return empty($errorMessages);
    }

    public function getAllErrors()
    {
        return $this->errorMessages;
    }
}

?>