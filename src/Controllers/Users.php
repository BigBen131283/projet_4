<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Core\Request;
use App\Repository\UsersDB;
use App\Core\Controller;
use App\Validator\UsersValidator;
use App\Validator\ErrorMessages;

class Users extends Controller
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

    public function login()
    {
        $this->render('users/login', [], "html");
    }

    public function register()
    {
        $request = new Request;
        $validator = new UsersValidator();

        if($request->isPost())
        {
            $body = $request->getBody();
            $dbAccess = new UsersDB();

            // On appelle ton validateur en lui passant les données
            $errorMessages = $validator->checkUserEntries($body);
            if(!$errorMessages->hasError())
            {
                $dbAccess->createUser($body, $this);
                $this->render('home/index');
            }
        }
        else
        {
            $errorMessages = $validator->checkUserEntries([]);
        }
        var_dump($errorMessages->getAllErrors());
        $this->render('users/register', ['errorMessages' => $errorMessages], "html");
    }
}

?>