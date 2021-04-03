<?php

require_once 'dao/UserDao.php';

class Auth {

    private $pdo;
    private $base;

    public function __construct(PDO $pdo, $base) {
        $this->pdo = $pdo;
        $this->base = $base;
    }

    public function checkToken() {
        if (!empty($_SESSION['token'])) {
            $token = $_SESSION['token'];
            $userDao = new UserDao($this->pdo);
            $user = $userDao->findByToken($token);
            if ($user) {
                return $user;
            }
        }

        header("Location: ".$this->base."/login.php");
        exit;
    }

}