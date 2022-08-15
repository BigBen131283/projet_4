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
                        "rules"=>[self::RULE_NOTEMPTY, ["rule"=>self::RULE_MIN, 'length'=>4], ["rule"=>self::RULE_MAX, 'length'=>8]]
                    ],
                    "pass"=>[
                        "value"=>$params['pass'],
                        "rules"=>[self::RULE_NOTEMPTY, ["rule"=>self::RULE_MIN, 'length'=>6]]
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
        
        public function checkUpdateEntries($params)
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
                    "profile_picture"=>[
                        "value"=>$params['profile_picture'],
                        // "rules"=>[self::RULE_FILETYPE, ["rule"=>self::RULE_FILESIZE, 'tmax'=>1]]
                        "rules"=>[["rule"=>self::RULE_FILETYPE,'location'=>'profile_pictures', 'fileaccesskey' => 'profile_picture'], 
                                  ["rule"=>self::RULE_FILESIZE, 'tmax'=>1, 'fileaccesskey' => 'profile_picture']]
                    ]
                ];
            }
            return $this->check($fieldsAndRules);
        }
    }

?>