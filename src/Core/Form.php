<?php

namespace App\Core;

class Form
{
    private $formCode = "";

    /**
     * générer un formulaire
     *
     * @return string
     */
    public function create()
    {
        return $this->formCode;
    }

    /**
     * Valide si tous les champs sont remplis
     *
     * @param array $form Tableau contenant les champs à vérifier (en général issus de $_POST ou $_GET)
     * @param array $fields Tableau listant les champs à vérifier
     * @return boolean
     */
    public static function validate(array $form, array $fields)
    {
        // On parcourt chaque champ
        foreach($fields as $field)
        {
            // Si le champ est absent ou vide dans le tableau
            if(!isset($form[$field]) || empty($form[$field]))
            {
                // On sort en retournant false
                return false;
            }
        }
        // Ici le formulaire est valide
        return true;
    }

    
}

?>