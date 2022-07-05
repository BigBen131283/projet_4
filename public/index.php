<?php

use App\Autoloader;
use App\Models\UsersModel;
use App\Users\Users;

require_once '../classes/php/Autoloader.php';
Autoloader::register();

$users = new UsersModel;

// $author = $users->findBy(['role' => 10]);
// $author = $users->findAll();
// $author = $users->find(6);
$author = $users
    ->setEmail('nouveau-mail@free.fr')
    ->setPassword('5678')
    ->setPseudo('NouveauLecteur')
    ->setStatus(20)
    ->setRole(20);
echo '<br/>';
echo '<br/>';
echo '<br/>';

var_dump($author);

?>