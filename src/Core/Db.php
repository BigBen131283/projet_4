<?php

namespace App\Core;

// On "immporte" PDO
use PDO;
use PDOException;

class Db extends PDO
{
    // Instance unique de la classe
    private static $instance;

    // Informations de connexion
    private const DBHOST = 'localhost';
    private const DBUSER = 'root';
    private const DBPASS = 'root';
    private const DBNAME = 'projet4';

    /**
     * Constructeur de la classe
     * La classe ne peut pas être instanciée spécifiquement
     * On doit utiliser une méthode statique qui permettra d'obtenir l'instance
     */
    public function __construct()
    {
        // DSN de connexion
        $_dsn = 'mysql:dbname=' . self::DBNAME . ';host=' . self::DBHOST;

        // On appelle le constructeur de la classe PDO
        /**
         * On essaye de se connecter à la base de données
         * Si on n'y arrive on affiche un message d'erreur
         * 
         * FETCH_ASSOC : A chaque fois qu'on fait un FETCH on obtient un tableau associatif
         * c'est à dire 'Nom de colonne' => 'Valeur'
         * 
         * ERRMODE_EXCEPTION : Déclenche une exception dès lors qu'on a un pb
         */
        try
        {
            parent::__construct($_dsn, self::DBUSER, self::DBPASS);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->exec('SET lc_time_names = \'fr_FR\'');
        }
        catch(PDOException $e)
        {
            die($e->getMessage());
        }
    }

    /**
     * Vérifie s'il y a déjà une instance
     * En créé une s'il n'y en pas
     *
     * @return self
     */
    public static function getInstance():self
    {
        if(self::$instance === null)
        {
            self::$instance = new self();
        }
        return self::$instance;
    }
}

?>