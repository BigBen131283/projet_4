<?php

namespace App\Core;

class Model extends Db
{
    // Table de la base de données
    protected $table;

    // Instance de Db
    private $db;

    /**
     * READ
     * 
     * Va rechercher tous les enregistrements d'une table
     * Permet d'interroger la base de données par l'intermédiaire de l'héritage
     *
     * @return object
     */
    public function findAll():object
    {
        $query = $this->requete('SELECT * FROM ' . $this->table);
        return $query->fetchAll();
    }
    
    /**
     * READ
     * 
     * Va rechercher un ou plusieurs enregistrement(s) d'une table selon des critères indiqués
     *
     * @param array $criteres
     * 
     */
    public function findBy(array $criteres)
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
        // var_dump($liste_champs);
        // echo '<br/>';
        // var_dump($valeurs);
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
     * @return object
     */
    public function find(int $id):object
    {
        return $this->requete("SELECT * FROM {$this->table} WHERE id = $id")->fetch();
    }

    /**
     * CREATE
     * 
     * Ajouter une entrée dans la base en fonction du $model
     * Permet d'ajouter dans n'importe quelle base de données une nouvelle entrée
     * Gère en paramètres les champs
     *
     * @param Model $model
     * @return object
     */
    public function create(Model $model):object
    {
        $champs = [];
        $inter = [];
        $valeurs = [];

        // On boucle pour éclater le tableau
        foreach($model as $champ => $valeur)
        {
            //INSERT INTO table (liste de champs ex: email, Password, Pseudo, Status, Role) VALUES (?, ?, ?, ?, ?, ?)
            if($valeur != null && $champ != 'db' && $champ !='table'){
                $champs[] = $champ;
                $inter[] = "?";
                $valeurs[] = $valeur;
            }
        }
        // On transforme le tableau "champs" en une chaîne de caratères
        $liste_champs = implode(', ', $champs);
        $liste_inter = implode(', ', $inter);

        return $this->requete('INSERT INTO ' . $this->table . ' (' . $liste_champs . ') VALUES(' . $liste_inter . ')', $valeurs);        
    }

    /**
     * UPDATE
     * 
     * Mise à jour des données d'une table à partir de l'id
     *
     * @param integer $id
     * @param Model $model
     * @return object
     */
    public function update(int $id, Model $model):object
    {
        $champs = [];
        $valeurs = [];

        // On boucle pour éclater le tableau
        foreach($model as $champ => $valeur)
        {
            //UPDATE table SET ex : email = ?, password = ?, pseudo = ?, status = ?, role = ? WHERE id= ?
            if($valeur !== null && $champ != 'db' && $champ != 'table')
            {
                $champs[] = "$champ = ?";
                $valeurs[] = $valeur;
            }
        }
        $valeurs[] = $id;

        //On transforme le tableau "champs" en une chaîne de caractères
        $liste_champs = implode(', ', $champs);
        // var_dump($liste_champs);
        // var_dump($valeurs);
        // die();
        // On exécute la requête
        return $this->requete('UPDATE ' . $this->table . ' SET ' . $liste_champs . ' WHERE id = ?', $valeurs);
    }

    public function delete(int $id)
    {
        return $this->requete("DELETE FROM {$this->table} WHERE id = ?", [$id]);
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
            // var_dump($attributs);
            $query->execute($attributs);
            return $query;
        }
        else
        {
            // Requête simple
            return $this->db->query($sql);
        }
    }

    /**
     * Permet de transformer un tableau reçu en objet
     * Utile par exemple quand on reçoit les données d'un formulaire
     *
     * @param array $donnees
     * @return object
     */
    public function hydrate(array $donnees):object
    {
        foreach($donnees as $key => $value)
        {
            //On récupère le nom du setter correspondant à la clé (key)
            // titre -> setTitre
            $setter = 'set'.ucfirst($key);

            //On vérifie si le setter existe
            if(method_exists($this, $setter))
            {
                // on appelle le setter
                $this->$setter($value);
            }
        }
        return $this;
    }
}

?>