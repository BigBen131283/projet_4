<?php

    namespace App\Validator;

use Exception;

    class UsersValidator
    {
        private const BAD_EMAIL = 'Email incorrect';
        private const BAD_FIELDS = 'Tous les champs doivent être renseignés';
        private const BAD_PASSWORD = 'Les deux mots de passe doivent être identiques.';

        public function checkUserEntries(array $params)
        {
            $email = $params['email'];
            $password = $params['pass'];
            $passCheck = $params['confirm-pass'];
            $pseudo = $params['pseudo'];
            $array = [];

            if(empty($pseudo) || empty($password) || empty($passCheck) || empty($email))
            {
                // throw new Exception('Tous les champs doivent être renseignés');
                $array['fields'][] = self::BAD_FIELDS;
            }
            if($password !== $passCheck)
            {
                // throw new Exception('Les deux mots de passe doivent être identiques.');
                $array['password'][] = self::BAD_PASSWORD;
            }
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                // throw new Exception('Email incorrect.');
                $array['email'][] = self::BAD_EMAIL;
            }
            return $array;
        }
    }

?>