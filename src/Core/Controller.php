<?php

// Ceci est notre contrôleur principal
// On y trouve le rendu des vues

namespace App\Core;

abstract class Controller
{
    public function render(string $fichier, array $donnees = [])
    {
        // On extrait le contenu de $donnees
        extract($donnees);

        // On créé le chemin vers la vue
        require_once ROOT.'./src/View/'.$fichier.'.php';

    }
}

?>