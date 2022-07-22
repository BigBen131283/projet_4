<?php

namespace App\Controllers;

use App\Core\Request;
use App\Repository\UsersDB;
use App\Core\Controller;
use App\Validator\UsersValidator;
use App\Core\Logger;
use App\Core\Main;
use App\Core\MailTrap;
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
                
                $credentials = $dbAccess->login($body);
                if($credentials !== null)
                {
                    // ici tout est ok
                    Main::$main->login($credentials->id);
                    Main::$main->response->redirect('/');
                }
                else
                {
                    $validator->addError('loginerror', 'Pseudo inconnu ou mot de passe erroné ou compte en attente de validation');
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

                if($dbAccess->createUser($body))
                {
                    $email = $body['email'];
                    $pseudo = $body['pseudo'];
                    $mail = new MailTrap($email);

                    $result = $mail->sendRegisterConfirmation("Please $pseudo, confirm your registration", $pseudo);

                    if($result)
                    {
                        // Main::$main->response->redirect('/');
                        $validator->addError('flashmessage', 'Confirmez votre inscription grâce au mail que nous vous avons envoyé');
                        $this->render('users/login', "php", 'defaultLogin', ['errorHandler' => $validator]);
                    }
                    else
                    {
                        $validator->addError('flashmessage', 'Oups, pb email');
                    }
                }
                else
                {
                    $validator->addError('flashmessage', 'Oups, pb bdd');
                }
            }    
            $this->render('users/register', "php", 'defaultLogin', ['errorHandler' => $validator]);        
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
        $logger = new Logger(__CLASS__);
        $usersDB = new UsersDB();
        $request = new Request();
        $uri = $_SERVER['REQUEST_URI'];
        $uricomponents = parse_url($uri);
        parse_str($uricomponents['query'], $params);
        $selector = $params['selector'];
        $token = $params['token'];
        try{
            if($request->isGet()&& $selector) {
                if($usersDB->confirmRegistration($selector, $token)) {
                    $logger->db('User registration confirmation failed');
                    Main::$main->response->redirect('/');
                }
            }
            else {
                $logger->db('Invalid register confirmation request');
                Main::$main->response->redirect('/');
            }
        }
        catch(PDOException  $e) {
            $logger->db($e->getMessage());
            Main::$main->response->redirect('/');
        }
        $logger->db('Confirmation request processed for ');
        Main::$main->response->redirect('/users/login');
    }
}
?>