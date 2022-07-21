<?php

    namespace App\Validator;

    use App\Core\Validator;

    class UsersValidator extends Validator
    {
        public function __construct()
        {
            parent::__construct(__CLASS__);
        }

        public function checkUserEntries(array $params)
        {
            $fieldsAndRules=[];
            if(!empty($params))
            {
                $fieldsAndRules = [
                    "email"=>[
                        "value"=>$params['email'],
                        "rules"=>[self::RULE_EMAIL, self::RULE_NOTEMPTY]
                    ],
                    "pseudo"=>[
                        "value"=>$params['pseudo'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ],
                    "pass"=>[
                        "value"=>$params['pass'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ],
                    "confirm-pass"=>[
                        "value"=>$params['confirm-pass'],
                        "rules"=>[self::RULE_NOTEMPTY, ["rule"=>self::RULE_MATCH,'match'=>'pass']],
                        'label'=>'Mot de passe'
                    ]
                ];
            }
            return $this->check($fieldsAndRules);
        }


        
        // -------------------------------------------------------------------

        public function checkLoginEntries($params)
        {
            $fieldsAndRules=[];
            if(!empty($params))
            {
                $fieldsAndRules = [
                    "pseudo"=>[
                        "value"=>$params['pseudo'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ],
                    "pass"=>[
                        "value"=>$params['pass'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ]
                ];
            }
            return $this->check($fieldsAndRules);
        }
    }

?>