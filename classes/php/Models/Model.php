<?php

namespace App\Models;

use App\Database\Db;

class Model extends Db
{
    // Table de la base de données
    protected $table;

    // Instance de Db
    private $db;

    /**
     * READ
     * Va rechercher tous les enregistrements d'une table
     * Permet d'interroger la base de données par l'intermédiaire de l'héritage
     *
     * @return array
     */
    public function findAll():array
    {
        $query = $this->requete('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }
    
    /**
     * READ
     * Va rechercher un ou plusieurs enregistrement(s) d'une table selon des critères indiqués
     *
     * @param array $criteres
     * @return array
     */
    public function findBy(array $criteres):array
    {
        $champs = []; // Contient les champs de $criteres
        $valeurs = []; // Contient les valeurs de $criteres

        // On boucle pour éclater le tableau $criteres
        foreach($criteres as $champ => $valeur)
        {
            // SELECT * FROM * table WHERE nom de la colonne = ? (valeur cherchée)
            // bindValue(1, valeur)
            $champs[] = "$champ = ?";
            $valeurs[] = $valeur;
        }

        // On transforme le tableau "champs" en une chaîne de cararètres (string)
        $liste_champs = implode(' AND ', $champs);
        // var_dump($liste_champs, $valeurs);
        $id = 0;
        // On exécute la requête
        $query = $this->requete('SELECT * FROM ' . $this->table . ' WHERE ' . $liste_champs, $valeurs);
        return $query->fetchAll();
    }

    /**
     * READ
     * récupère un élément par son id
     *
     * @param integer $id
     * @return array
     */
    public function find(int $id):array
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }

    public function create(UsersModel $user)
    {

    }

    /**
     * Fonction générique utilisée dans les différentes fonctions du CRUD ci-dessus
     *
     * @param string $sql contient toute la requête avec les ?
     * @param array|null $attributs contient autant d'attirbuts qu'il y a de ? dans la requête
     * @return object
     */
    public function requete(string $sql, array $attributs = null)
    {
        // On récupère l'instance de Db
        $this->db = Db::getInstance();

        // On vérifier si on a des attributs
        if($attributs !== null)
        {
            // Requête préparée
            $query = $this->db->prepare($sql);
            $query->execute($attributs);
            return $query;
        }
        else
        {
            // Requête simple
            return $this->db->query($sql);
        }
    }
}

?>