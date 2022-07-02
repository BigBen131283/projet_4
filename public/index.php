<?php

use app\core\Request;

require_once '../Core/Request.php';

$request = new Request();


$path = $request->getPath();
// echo '</br>';
$method = $request->method();

$body = $request->getBody();
var_dump($body);

if($path === '/register')
{
    readfile("../View/register.html");
}

?>