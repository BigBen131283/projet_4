<?php

namespace App\Controllers;

use App\Core\Request;
use App\Repository\UsersDB;
use App\Core\Controller;
use App\Core\Flash;
use App\Validator\UsersValidator;
use App\Core\Logger;
use App\Core\Main;
use App\Core\Mailo2;
use Exception;
use PDOException;

class UsersController extends Controller
{
    public function login()
    {
        $request = new Request;
        $logger = new Logger(Users::class);
        $user = Main::$main->getUsersModel();

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
                    Main::$main->response->redirect('/#accueil');
                }
                else
                {
                    $validator->addError('loginerror', 'Pseudo inconnu ou mot de passe erroné ou compte en attente de validation');
                    $this->render('users/login', "php", 'defaultLogin', ['loggedUser'=>$user, 'errorHandler' => $validator]);
                }
            }
            else
            {
                $this->render('users/login', "php", 'defaultLogin', ['loggedUser'=>$user, 'errorHandler' => $validator]);
            }
        }
        else
        {   
            // This is perhaps a get, send an empty error array

            $this->render('users/login', "php", 'defaultLogin', ['loggedUser'=>$user, 'errorHandler' => $validator]);
        }
    }

    public function register()
    {
        $request = new Request;
        $logger = new Logger(Users::class);
        $user = Main::$main->getUsersModel();

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
                    $mail = new Mailo2($email);

                    $result = $mail->sendRegisterConfirmation("Please $pseudo, confirm your registration", $pseudo);

                    if($result)
                    {
                        $flash = new Flash();
                        $flash->addFlash('register', 'Confirmez votre inscription grâce au mail que nous vous avons envoyé');
                        Main::$main->response->redirect('/users/login');
                        // $validator->addError('flashmessage', 'Confirmez votre inscription grâce au mail que nous vous avons envoyé');
                        // $this->render('users/login', "php", 'defaultLogin', ['errorHandler' => $validator]);
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
            $this->render('users/register', "php", 'defaultLogin', ['loggedUser'=>$user, 'errorHandler' => $validator]);        
        }
        $this->render('users/register', "php", 'defaultLogin', ['loggedUser'=>$user, 'errorHandler' => $validator]);
    }

    public function logout()
    {
        Main::$main->logout();
        Main::$main->response->redirect('/#accueil');
    }

    public function profil()
    {
        $request = new Request;
        $logger = new Logger(__CLASS__);
        $usersModel = Main::$main->getUsersModel();
        $dbAccess = new UsersDB();
        $validator = new UsersValidator();
        $user = Main::$main->getUsersModel();
        // get current pseudo, picture and mail values before calling the form
        // Whenever it's a get or post
        $validator->addValue("email", $usersModel->getEmail());
        $validator->addValue("pseudo", $usersModel->getPseudo());
        $validator->addValue("profile_picture", $usersModel->getProfile_picture());

        if($request->isPost())
        {
            $body = $request->getBody();
            if($_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {  // Upload worked ? 
                $filename = $_FILES["profile_picture"]["name"];
                $filetype = $_FILES["profile_picture"]["type"];
                $filesize = $_FILES["profile_picture"]["size"];
                $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); 
                // $logger->console('*** Uploaded file :'.$filename.'.'.$filetype);
                $validator->addValue("profile_picture", $filename.'.'.$filetype);

                $body['profile_picture'] = "$filename.'.'.$filetype"; // On ajoute manuellement l'image car elle ne s'ajoute automatiquement pas à la construction de $body
                $errorList = $validator->checkUpdateEntries($body);
                if(!$validator->hasError())
                {            
                    // On génère un nom unique
                    $newname = md5(uniqid());
                    // On génère le chemin complet
                    $newfilename = ROOT."/public".IMAGEROOT."$newname.$extension";
    
                    // On déplace le fichier de tmp à uploads en le renommant
                    if(!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $newfilename))
                    {
                        $validator->addError('uploadError', 'Déplacement fichier impossible.');
                        $this->render('users/profil', "php", 'defaultLogin', ['loggedUser'=>$user, 'updateUser' => $validator]);
                    }
                    else
                    {   // Finalement on réaffiche la form avec la nouvelle image
                        // fichier = '/var/www/vhosts/domaine.com/www/fichier.pdf';
                        // if(file_exists($fichier)){unlink($fichier);}
                        chmod($newfilename, 0644);  //on interdit l'exécution du fichier protection UNIX de directory et de fichier [owner group others] read write execute 700 111 000 000
                                                                                                                                                        //                  644 110 100 100
                                                                                                                                                        //                      rwx rwx rwx  
                        $previousimage = $usersModel->getProfile_picture();
                        if(file_exists(ROOT."/public".IMAGEROOT.$previousimage) && ($previousimage !== DEFAULTIMAGE)){
                            $logger->console('Remove previous picture : '.ROOT."/public".IMAGEROOT.$previousimage);
                            try 
                            {
                                unlink(ROOT."/public".IMAGEROOT.$previousimage); // unlink -> PHP efface l'image de la directory pour éviter de conserver trop d'images dans la base
                            }
                            catch(Exception $e) 
                            {
                                $logger->console('Cannot remove file');
                            }
                        }
                        $validator->addValue("profile_picture", $newname.'.'.$extension);
                        if($dbAccess->updateUser($usersModel->getId(), 
                                            $body['email'],
                                            $body['pseudo'], 
                                            $newname.'.'.$extension))
                        {
                            $flash = new Flash();
                            $flash->addFlash('update', 'Profil mis à jour');
                        }
                        else 
                        {
                            $validator->addError('uploadError', 'Could not update your profile in the DB');
                        }
                    }
                }
            }
            else 
            {
                if($_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE)
                {
                    if($dbAccess->updateUser($usersModel->getId(), 
                                             $body['email'],
                                             $body['pseudo'],
                                             $usersModel->getProfile_picture()))
                    {
                        $flash = new Flash();
                        $flash->addFlash('update', 'Profil mis à jour');
                    }
                    else 
                    {
                        $validator->addError('uploadError', 'Could not update your profile in the DB');
                    }
                }
                else
                {
                $validator->addError('uploadError', 'Upload error.');
                $this->render('users/profil', "php", 'defaultLogin', ['loggedUser'=>$user, 'updateUser' => $validator]);
                }
            }
        }
        $this->render('users/profil', 'php', 'defaultLogin', ['loggedUser'=>$user, 'updateUser' => $validator]);
    }

    public function resetrequest() 
    {
        $request = new Request;
        $logger = new Logger(Users::class);
        $user = Main::$main->getUsersModel();
        $validator = new UsersValidator();

        if($request->isPost())
        {
            $body = $request->getBody();
            // On appelle ton validateur en lui passant les données
            $errorList = $validator->checkPasswordResetRequest($body);
            if(!$validator->hasError())
            {
                $dbAccess = new UsersDB();
                $user = $dbAccess->getUserbyEmail($body['email']);
                if($user)
                {
                    $logger->console('Sending the password reset email to user');
                    $mail = new Mailo2($user->email);

                    $result = $mail->sendPasswordReset("Bonjour $user->pseudo, veuillez cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe", $user->pseudo);
                    Main::$main->response->redirect('/users/login');
                }
                else
                {
                    $validator->addError('email', 'email inconnu');
                    $user = Main::$main->getUsersModel();
                }
            }
        }
        $this->render('users/passwordresetrequest', 'php', 'defaultLogin', ['loggedUser'=>$user, 'errorHandler' => $validator]);
    }

    public function passwordReset()
    {
        $logger = new Logger(Users::class);
        $user = Main::$main->getUsersModel();
        $request = new Request();
        $validator = new UsersValidator();
        $usersdb = new UsersDB();

        $body = $request->getBody();
        // retrieve a selector to get user pseudo in the resets table
        $urlelements = explode('/', $_SERVER['REQUEST_URI']);   // The selector is transmitted by the user email request
        if(count($urlelements) === 4)
        {
            $selector = $urlelements[3];                            // Look at passwordresetconfirmed()
        }
        else
        {
            Main::$main->response->redirect('/redirect/error');
        }
        if($request->isPost())
        {
            $errorList = $validator->checkPasswordResetFinal($body);
            if(!$validator->hasError())
            {
                $dbAccess = new UsersDB();            
                $reset = $usersdb->getPseudobySelector($selector);      // Read the resets table to get user pseudo
                $user = $usersdb->getUserbyPseudo($reset->pseudo);      // Get the user
                if($user)
                {
                    $newpass = $body['pass'];
                    $usersdb->updatePassword($user->id, $newpass);     // Reset the user password
                    Main::$main->response->redirect('/users/login');
                }
                else
                {
                    $message = 'Call IT dev, big big problem here !!!';
                    Main::$main->response->redirect("/redirect/error/$message");
                }
            }
        }
        $this->render('users/passwordreset', 'php', 'defaultLogin', ['loggedUser'=>$user, 
                                                                     'errorHandler' => $validator]);
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
        try
        {
            if($request->isGet()&& $selector) {
                if($usersDB->confirmRegistration($selector, $token)) {
                    Main::$main->response->redirect('/#accueil');
                }
            }
            else {
                $logger->db('Invalid register confirmation request');
                Main::$main->response->redirect('/#accueil');
            }
        }
        catch(PDOException  $e) {
            $logger->db($e->getMessage());
            Main::$main->response->redirect('/#accueil');
        }
        $logger->db('Confirmation request processed for ');
        Main::$main->response->redirect('/users/login');
    }

    public function passwordresetconfirmed()
    {
        $logger = new Logger(__CLASS__);
        $usersDB = new UsersDB();
        $request = new Request();
        $uri = $_SERVER['REQUEST_URI'];
        $uricomponents = parse_url($uri);
        parse_str($uricomponents['query'], $params);
        $selector = $params['selector'];
        $token = $params['token'];
        try
        {
            if($request->isGet()&& $selector) 
            {
                if($usersDB->confirmPasswordReset($selector, $token)) 
                {
                    Main::$main->response->redirect("/users/passwordreset/$selector");
                }
                else 
                {
                    $message = 'Votre requête de réinitialisation du mot de passe est invalide.';
                    Main::$main->response->redirect("/redirect/error/?errmsg=$message");
                }
            }
            else 
            {
                $message = 'Oups, vous devriez faire demi-tour...';
                Main::$main->response->redirect("/redirect/error/?errmsg=$message");
            }
        }
        catch(PDOException  $e) {
            $logger->db($e->getMessage());
            Main::$main->response->redirect("/redirect/error/?errmsg=$e->getMessage()");
        }
    }
}
?>