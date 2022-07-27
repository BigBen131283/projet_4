<?php

    namespace App\Validator;

    use App\Core\Validator;

    class BilletValidator extends Validator
    {
        public function __construct()
        {
            parent::__construct(__CLASS__);
        }

        public function checkBilletEntries(array $params)
        {
            $fieldsAndRules=[];
            if(!empty($params))
            {
                $fieldsAndRules = [
                    "title"=>[
                        "value"=>$params['title'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ],
                    "abstract"=>[
                        "value"=>$params['abstract'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ],
                    "chapter"=>[
                        "value"=>$params['chapter'],
                        "rules"=>[self::RULE_NOTEMPTY]
                    ]
                ];
            }
            return $this->check($fieldsAndRules);
        }
    }

?>