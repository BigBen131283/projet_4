<?php

// Ceci est notre contrôleur principal pour le rendu des vues

namespace App\Core;

abstract class Controller
{
    public function render(string $fichier, string $extension, string $layout = 'default', array $donnees = [])
    {
        // On extrait le contenu de $donnees
        extract($donnees);

        // On démarre le buffer de sortie
        ob_start();
        // A partir de ce point toute sortie est conservée en mémoire

        // On créé le chemin vers la vue
        require_once ROOT.'./src/View/'.$fichier.".$extension";

        $contenu = ob_get_clean();
       
        require_once ROOT.'./src/View/Templates/'.$layout.'.php';

    }
}

?>