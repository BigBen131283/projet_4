<?php

namespace App\Controllers;

use App\Models\UsersModel;

class UsersController extends Controller
{
    /**
     * Cette méthode affichera une page listant tous les membres actifs
     *
     * @return void
     */
    public function index()
    {
        // On instancie le modèle correspondant à la table "users"
        $usersModel = new UsersModel;

        // On va chercher tous les utilisateurs
        $users = $usersModel->findBy(['status' => 20]);

        // On génère la vue
        $this->render('users/index', ['users' => $users]);
    }

    /**
     * Cette méthode affiche 1 utilisateur
     *
     * @param integer $id Id de l'utilisateur
     * @return void
     */
    public function profil(int $id)
    {
        // On instancie le modèle
        $usersModel = new UsersModel;

        // On va chercher un utilisateur 
        $user = $usersModel->find($id);

        // On envoie à la vue
        $this->render('users/profil', compact('user'));
    }
}

?>