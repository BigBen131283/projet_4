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

$donnees = [
    'email' => 'email.hydrate@free.fr',
    'password' => '4321',
    'pseudo' => 'pseudo.hydrate',
    'status' => 20,
    'role' => 20
];

$member = $users->hydrate($donnees);

echo '<br/>';
var_dump($member);
echo '<br/>';

// $users->create($member);

echo '<br/>';



?>