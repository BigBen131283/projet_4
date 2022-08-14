<?php

use App\Autoloader;
use App\Core\Main;


// On définit une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));
define('IMAGEROOT', '/images/profile_pictures/');
define('DEFAULTIMAGE', 'defaultuserpicture.png');
define('IMAGEROOTCHAPTER', '/images/chapter_pictures/');
define('DEFAULTIMAGECHAPTER', 'default.jpg');
// var_dump(ROOT);
// On importe l'autoloader
require_once ROOT.'./src/Autoloader.php';
Autoloader::register();

// On instancie Main (notre routeur)
$app = new Main();

// On démarre l'application
$app->start();

?>