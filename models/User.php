<?php

class User {
    public $id;
    public $email;
    public $password;
    public $name;
    public $birthdate;
    public $city;
    public $work; 
    public $avatar;
    public $cover;
    public $token;
}

interface ud {
    public function findByToken($token);
    public function findByEmail($email);
    public function findById($id);
    public function update(User $user);
    public function insert(User $user);
}