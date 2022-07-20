<?php

namespace App\Controllers;

use App\Models\UsersModel;
use App\Core\Request;
use App\Repository\UsersDB;
use App\Core\Controller;
use App\Validator\UsersValidator;
use App\Core\Logger;
use App\Core\Main;
use App\Core\Mail;
use PDOException;

class UsersController extends Controller
{
    public function login()
    {
        $request = new Request;
        $logger = new Logger(Users::class);

        $validator = new UsersValidator();
        if($request->isPost())
        {
            $logger->console("Check login data");
            $body = $request->getBody();
            $errorList = $validator->checkLoginEntries($body);
            $logger->console($validator->getValue('pseudo'));
            if(!$validator->hasError())
            {
                $logger->console("No error, try login");
                $dbAccess = new UsersDB();
                try
                {
                    $credentials = $dbAccess->login($body);
                    if($credentials !== null)
                    {
                        // ici tout est ok
                        Main::$main->login($credentials->id);
                        Main::$main->response->redirect('/');
                        

                    }
                    else
                    {
                        $validator->addError('loginerror', 'Pseudo inconnu ou mot de passe erroné');
                        $logger->console($validator->getValue('pseudo'));
                        $this->render('users/login', "php", 'defaultLogin', ['errorHandler' => $validator]);
                    }                    
                }
                catch(PDOException  $e) 
                {
                    $logger->console($e->getMessage());
                    $validator->addError('loginerror', 'Problème de base de données'. $e->getCode());
                    $this->render('users/login', "php", 'defaultLogin', ['errorHandler' => $validator]);
                }
            }
            else
            {
                $this->render('users/login', "php", 'defaultLogin', ['errorHandler' => $validator]);
            }
        }
        else
        {   
            // This is perhaps a get, send an empty error array

            $this->render('users/login', "php", 'defaultLogin', ['errorHandler' => $validator]);
        }
    }

    public function register()
    {
        $request = new Request;
        $logger = new Logger(Users::class);

        $validator = new UsersValidator();
        if($request->isPost())
        {
           
            $logger->console("Check register data");
            $body = $request->getBody();
            // On appelle ton validateur en lui passant les données
            $errorList = $validator->checkUserEntries($body);
            if(!$validator->hasError())
            {
                $logger->console("No error, insert in DB");
                $dbAccess = new UsersDB();
                try{
                    $dbAccess->createUser($body);
                    $email = $body['email'];
                    $pseudo = $body['pseudo'];
                    $mail = new Mail($email);

                    // $result = $mail->sendRegisterConfirmation("Please $pseudo, confirm your registration", $pseudo);
                    $result = true;

                    if($result)
                    {
                        Main::$main->response->redirect('/');
                    }
                    else
                    {
                        $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);
                    }
                }
                catch(PDOException  $e) {
                    $logger->console($e->getMessage());
                    $validator->addError('email', 'Email déjà existant.'. $e->getCode());
                    $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);
                }
            }            
        }
        $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);
    }

    public function logout()
    {
        Main::$main->logout();
        Main::$main->response->redirect('/');
    }

    public function profil()
    {
        $this->render('users/profil', 'php', 'defaultLogin', ['loggedUser' => Main::$main->getUsersModel()]);
    }

    public function registerconfirmed() 
    {
        $logger = new Logger(Users::class);
        $dbAccess = new UsersDB();
        try{
            $request = new Request();
            $uri = $_SERVER['REQUEST_URI'];
            $uricomponents = parse_url($uri);
            parse_str($uricomponents['query'], $params);
            $pseudo = $params['pseudo'];
            if($request->isGet()) {
                $dbAccess->confirmRegistration($pseudo);
            }
        }
        catch(PDOException  $e) {
            $logger->console($e->getMessage());
            Main::$main->response->redirect('/');
        }
        Main::$main->response->redirect('/users/login');
    }
}
?>