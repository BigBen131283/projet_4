<?php

    namespace App\Validator;

    use Exception;
    use App\Core\Logger;

    class UsersValidator
    {
        private const BAD_EMAIL = 'Email incorrect';
        private const BAD_FIELDS = 'Ce champ doit être renseigné';
        private const BAD_PASSWORD = 'Les deux mots de passe doivent être identiques';
        private $logger;

        private $errors = [];
        private $values = [];       // Used to remember previously entered values

        public function __construct()
        {
            $this->logger = new Logger(UsersValidator::class);
        }

        public function checkUserEntries(array $params): array
        {
            if(!empty($params))
            {
                $this->values["email"] = $params['email'];
                $this->values["pass"] = $params['pass'];
                $this->values["confirm-pass"] = $params['confirm-pass'];
                $this->values["pseudo"] = $params['pseudo'];

                if(empty($this->values["email"]))
                {
                    $this->addError('email', self::BAD_FIELDS);
                }
                if(!filter_var($this->values["email"], FILTER_VALIDATE_EMAIL))
                {
                    $this->addError('email', self::BAD_EMAIL);
                }

                if(empty($this->values["pseudo"]))
                {
                    $this->addError('pseudo', self::BAD_FIELDS);
                }
                if(empty($this->values["pass"]))
                {
                    $this->addError('password', self::BAD_FIELDS);
                }
                if(empty($this->values["confirm-pass"]))
                {
                    $this->addError('passCheck', self::BAD_FIELDS);
                }
                if($this->values["pass"] !== $this->values["confirm-pass"])
                {
                    $this->addError('passCheck', self::BAD_PASSWORD);
                }
            }
            return $this->errors;
        }
        // -------------------------------------------------------------------
        public function addError($attribute, $message)
        {
            $this->errors[$attribute][] = $message;
            $this->logger->console("Adding [$attribute] error message [$message]");
            return;
        }    
        // -------------------------------------------------------------------
        public function getFirstError($attribute)
        {
            if(!empty($this->errors)) {
                if(isset($this->errors["$attribute"][0]))
                {
                    return '<p class="myerror">'.$this->errors["$attribute"][0].'</p>';
                }
            }
            return '<p class="hidden"></p>';
        }
        // -------------------------------------------------------------------
        public function getValue($attribute) 
        {
            if(isset($this->values["$attribute"])) {
                return $this->values["$attribute"];
            }
            return '';
        }
        // -------------------------------------------------------------------
        public function hasError()
        {
            return !empty($this->errors);
        }

        public function getAllErrors()
        {
            return $this->errors;
        }

        public function checkLoginEntries()
        {
            if(!empty($params))
            {
                
                $this->values["pass"] = $params['pass'];
                $this->values["pseudo"] = $params['pseudo'];

                if(empty($this->values["pseudo"]))
                {
                    $this->addError('pseudo', self::BAD_FIELDS);
                }
                if(empty($this->values["pass"]))
                {
                    $this->addError('password', self::BAD_FIELDS);
                }
            }
            return $this->errors;
        }
    }

?>