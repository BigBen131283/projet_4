<?php

    namespace App\Validator;

    use App\Core\Validator;

    class CommentsValidator extends Validator
    {
        public function __construct()
        {
            parent::__construct(__CLASS__);
        }

        public function checkCommentsEntries(array $params)
        {
            $fieldsAndRules=[];
            if(!empty($params))
            {
                $fieldsAndRules = [
                    "content"=>[
                        "value"=>$params['content'],
                        "rules"=>[self::RULE_NOTEMPTY, ["rule"=>self::RULE_MAX, 'length'=>80]]
                    ],
                ];
            }
            return $this->check($fieldsAndRules);
        }
    }

?>