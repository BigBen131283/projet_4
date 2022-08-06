<?php

namespace App\Core;

use App\Controllers\HomeController;
use App\core\Request;
use App\core\Response;
use App\core\Session;
use App\Models\UsersModel;

class Main
{
    public Session $session;
    public static Main $main;
    private Logger $logger;
    private UsersModel $usersmodel;
    public Request $request;
    public Response $response;
    
    public function __construct()
    {
        $this->session = new Session();
        self::$main = $this;
        $this->usersmodel = new UsersModel();
        $this->request = new Request;
        $this->response = new Response;
        $this->logger = new Logger(__CLASS__);
        $userId = $this->session->get('userId');

        if($userId)
        {
            $this->usersmodel = new UsersModel($userId);
        }
    }
    
    public function start()
    {       
        // http://nom-du-site.projet/controleur/methode/paramètres
        // on veut :
        // ex: http://projet4/billets/chapitre/paragraphe
        // on utilisera
        // http://projet4/index.php?p=billets/chapitre/a
        // La règle de réécriture (htaccess) permet de recevoir dans un paramètre p ce que contient $_GET
        
        // On retire le "trailing slash" éventuel de l'URL
        // On récupère l'URL
        $uri = $_SERVER['REQUEST_URI'];

        // On vérifie que $uri n'est pas vide et se termine par un /
        // $uri[-1] : on considère $uri comme un tableau et on consulte le dernier caractère
        if(!empty($uri) && $uri != '/' && $uri[-1] === "/")
        {
            // On enlève le /
            $uri = substr($uri, 0, -1);
            
            // Pour éviter le duplicate content
            // On envoie un code de redirection permanente
            http_response_code(301);

            // On redirige vers l'URL sans le /
            header('Location: '.$uri);
        }

        // On gère les paramètres d'URL
        // p=controleur/methode/paramètres
        // On sépare les paramètres dans un tableau
        $params = explode('/', $_GET['p']);

        if($params[0] != '')
        {
            // Ici on a au moins 1 paramètre
            // On récupère le nom du contrôleur à instancier
            // On met une majuscule en 1ère lettre, on ajoute le namespace complet avant et on ajoute "Controller" après
            // 
            // array_shift 
            // Extrait la première valeur du tableau array et la retourne, en raccourcissant array d'un élément, 
            // et en déplaçant tous les éléments vers le bas. 
            // Toutes les clés numériques seront modifiées pour commencer à zéro pendant que les clés litérales ne seront pas affectées.
            //
            //
            $controller = '\\App\\Controllers\\'. ucfirst(array_shift($params)).'Controller';
            
            // On instancie le contrôleur 
            $controller = new $controller;

            // On récupère le deuxième paramètre d'URL
            // Permet entre autres de basculer sur les pages d'accueil des différentes sections du site
            $action = (isset($params[0])) ? array_shift($params) : 'index';
            // var_dump($controller, $action); die;
            if(method_exists($controller, $action))
            {
                // Si il reste des paramètres on les passe à la méthode
                // (isset($params[0])) ? $controller->$action($params) : $controller->$action();
                // Ici on récupère les paramètres
                // var_dump($controller, $action); 
                (isset($params[0])) ? call_user_func_array([$controller, $action] , $params) : $controller->$action();
            }
            else
            {
                http_response_code(404);
                echo "La page recherchée n'existe pas";
            }

        }
        else
        {
            // On n'a pas de paramètres
            // On instancie le contrôleur par défaut
            $controller = new HomeController;

            // On appelle la méthode index()
            $controller->index();
        }
    }

    public function login($userId)
    {
        $this->logger->console('login with Id : '.$userId);
        $this->session->set('userId', $userId);
    }

    public function logout()
    {
        $this->logger->console('logged out');
        $this->session->close('userId');
        $this->usersmodel = new UsersModel();
    }

    public function getUsersModel()
    {
        return $this->usersmodel;
    }
}

?>