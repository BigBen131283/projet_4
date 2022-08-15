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
                    ],
                    "publish_at"=>[
                        "value"=>$params['publish_at'],
                        "rules"=>[self::RULE_PUBLISHDATE]
                    ],
                    "chapter_picture"=>[
                        "value"=>$params['chapter_picture'],
                        "rules"=>[["rule"=>self::RULE_FILETYPE,'location'=>'chapter_pictures', 'fileaccesskey' => 'chapter_picture'],
                                  ["rule"=>self::RULE_FILESIZE, 'tmax'=>2, 'fileaccesskey' => 'chapter_picture']]
                    ]
                ];
            }
            return $this->check($fieldsAndRules);
        }
    }

?>