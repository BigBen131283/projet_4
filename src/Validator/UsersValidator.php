<?php

    namespace App\Validator;

    use Exception;

    class UsersValidator
    {
        private const BAD_EMAIL = 'Email incorrect';
        private const BAD_FIELDS = 'Ce champ doit être renseigné';
        private const BAD_PASSWORD = 'Les deux mots de passe doivent être identiques';

        public function checkUserEntries(array $params):object
        {
            $errorMessages = new ErrorMessages();
            
            if(!empty($params))
            {
                $email = $params['email'];
                $password = $params['pass'];
                $passCheck = $params['confirm-pass'];
                $pseudo = $params['pseudo'];

                if(empty($email))
                {
                    // throw new Exception('Ce champ doit être renseigné');
                    $errorMessages->addError('email', self::BAD_FIELDS);
                }
                if(empty($pseudo))
                {
                    // throw new Exception('Ce champ doit être renseigné');
                    $errorMessages->addError('pseudo', self::BAD_FIELDS);
                }
                if(empty($password))
                {
                    // throw new Exception('Ce champ doit être renseigné');
                    $errorMessages->addError('password', self::BAD_FIELDS);
                }
                if(empty($passCheck))
                {
                    // throw new Exception('Ce champ doit être renseigné');
                    $errorMessages->addError('passCheck', self::BAD_FIELDS);
                }
                if($password !== $passCheck)
                {
                    // throw new Exception('Les deux mots de passe doivent être identiques.');
                    $errorMessages->addError('passCheck', self::BAD_PASSWORD);
                }
                if(!filter_var($email, FILTER_VALIDATE_EMAIL))
                {
                    // throw new Exception('Email incorrect.');
                    $errorMessages->addError('email', self::BAD_EMAIL);
                }
            }
            return $errorMessages;
        }
    }

?>