<?php 

require_once 'config.php';
require_once 'models/Auth.php';

$name = filter_input(INPUT_POST, 'name');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$password = filter_input(INPUT_POST, 'password');
$birthdate = filter_input(INPUT_POST, 'birthdate');

if ($name && $email && $password && $birthdate) {
    $auth = new Auth($pdo, $base);
    $birthdate = explode('/', $birthdate);
    if (count($birthdate) != 3) {
        //
    }
}

$_SESSION['flash'] = "Campos não enviados.";
header("Location: ".$base."/signup.php");
exit;