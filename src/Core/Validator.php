<?php
    namespace App\Core;
    
    use App\Core\Logger;


    abstract class Validator
    {
        protected const RULE_EMAIL = 'Email incorrect';
        protected const RULE_NOTEMPTY = 'Ce champ doit être renseigné';
        protected const RULE_ALLNOTEMPTY = 'Tous les champs doivent être renseignés';
        protected const RULE_MATCH = 'Le contenu de ce champ doit être identique à {field}';
        
        private $logger;

        private $errors = [];
        private $values = [];       // Used to remember previously entered values

        public function __construct($theClass)
        {
            $this->logger = new Logger($theClass);
        }
        
        public function check(array $fieldsAndRules)
        {
            if(!empty($fieldsAndRules))
            {
                foreach($fieldsAndRules as $key=>$checkEntry)
                {
                    foreach($checkEntry["rules"] as $rule)
                    {
                        if(is_array($rule))
                        {
                            $compositRule = $rule;
                            $rule = $compositRule["rule"];
                        }
                        $this->logger->console('Clé :'.$key.' Règle :'.$rule);
                        if($rule === self::RULE_EMAIL && !filter_var($checkEntry["value"], FILTER_VALIDATE_EMAIL))
                        {
                            $this->addError($key, $rule);
                        }
                        if($rule === self::RULE_NOTEMPTY && !$checkEntry["value"])
                        {
                            $this->addError($key, $rule);
                        }
                        //&& ($fieldsAndRules["pass"]["value"] !== $fieldsAndRules["confirm-pass"]["value"]) pas générique
                        if($rule === self::RULE_MATCH)
                        {
                            $fieldName = $compositRule["match"];
                            $message = str_replace('{field}', $checkEntry['label'] ?? $fieldName, $rule);
                            $ref = $fieldsAndRules[$fieldName]["value"];
                            if($checkEntry['value'] !== $ref)
                            {
                                $this->addError($key, $message);
                            }                            
                        }
                    }
                }
            }
            $this->logger->log($this->errors);
            die;
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

        // -------------------------------------------------------------------
        public function getAllErrors()
        {
            return $this->errors;
        }

    }
?>