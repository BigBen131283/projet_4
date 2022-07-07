<?php

use App\Autoloader;
use App\Core\Main;


// On définit une constante contenant le dossier racine du projet
define('ROOT', dirname(__DIR__));
var_dump(ROOT);
// On importe l'autoloader
require_once ROOT.'../classes/php/Autoloader.php';
Autoloader::register();

// On instancie Main (notre routeur)
$app = new Main();

// On démarre l'application
$app->start();

?>