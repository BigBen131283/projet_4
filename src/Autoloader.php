<?php

namespace App;

class Autoloader
{
    /**
     * Function qui permet de lancer le spl_autoload_register(). Cette fonction détecte les chargements de classes
     * et lance une méthode 'autoload'. 
     * Static : méthode accessible sans avoir besoin d'instancier la classe
     * 
     */
    static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload'
        ]);
    }

    /**
     * 
     * 
     * @param [type] $class
     * On récupère dans $class la totalité du namespace de la classe concernée.
     * 
     * On découpe le namespace
     *          on passe de App\Users\Users à Users/Users.php
     * 
     * @return string
     */
    static function autoload($class)
    {
        // On retire App\
        $class = str_replace(__NAMESPACE__ . '\\', '', $class);

        // On remplace les \ par des /
        $class = str_replace('\\', '/', $class);

        $fichier = __DIR__ . '/' . $class . '.php';
        // On vérifie si le fichier existe
        if(file_exists($fichier))
        {
            require_once $fichier;
        }
    }
}

?>