<?php

namespace App\Controllers;

class UsersController extends Controller
{
    public function index()
    {
        $donnees = ['a', 'b'];
        include_once ROOT.'./View/Users/index.php';
    }
}

?>