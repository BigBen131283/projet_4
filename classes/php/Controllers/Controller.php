<?php

// Ceci est notre contrôleur principal
// On y trouve le rendu des vues

namespace App\Controllers;

abstract class Controller
{
    public function render(string $fichier, array $donnees = [])
    {
        // On extrait le contenu de $donnees
        extract($donnees);

        // On créé le chemin vers la vue
        require_once ROOT.'./View/'.$fichier.'.php';

    }
}

?>