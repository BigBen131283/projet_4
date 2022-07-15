<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Core\Request;
use App\Repository\UsersDB;
use App\Core\Controller;
use App\Validator\UsersValidator;
use App\Core\Logger;
use PDOException;

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
        $this->render('users/index', "php", 'defaultLogin', ['users' => $users]);
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
        $this->render('users/profil', "php", 'defaultLogin', compact('user'));
    }

    public function login()
    {
        $this->render('users/login', "php", 'defaultLogin', []);
    }

    public function register()
    {
        $request = new Request;
        $logger = new Logger(Users::class);

        $validator = new UsersValidator();
        if($request->isPost())
        {
            $logger->console("Check post data");
            $body = $request->getBody();
            // On appelle ton validateur en lui passant les données
            $errorList = $validator->checkUserEntries($body);
            if(!$validator->hasError())
            {
                $logger->console("No error, insert in DB");
                $dbAccess = new UsersDB();
                try{
                    $dbAccess->createUser($body, $this);
                    $this->render('home/index', 'php');
                }
                catch(PDOException  $e) {
                    $logger->console($e->getMessage());
                    $validator->addError('email', 'Email déjà existant.'. $e->getCode());
                    $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);
                }
            }
            else {
                $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);
            }
        }
        else
        {   // This a get, send an empty error array
            $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);
        }
    }
}
?>