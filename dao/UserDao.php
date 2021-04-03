<?php 

require_once 'models/User.php';

class UserDao implements ud {

    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    private function generateUser($array) {
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->email = $array['email'] ?? '';
        $u->name = $array['name'] ?? '';
        $u->birthdate = $array['birthdate'] ?? '';
        $u->city = $array['city'] ?? '';
        $u->work = $array['work'] ?? '';
        $u->avatar = $array['avatar'] ?? '';
        $u->cover = $array['cover'] ?? '';
        $u->token = $array['token'] ?? '';
        return $u;
    }

    public function findByToken($token) {
        if (!empty($token)) {
            $conn = $this->pdo->prepare("SELECT * FROM users WHERE token = :token");
            $conn->bindValue(':token', $token);
            $conn->execute();
            if ($conn->rowCount() > 0) {
                $data = $conn->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
        } 
        
        return false;
        
    }
}