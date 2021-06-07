<?php 

require_once 'models/User.php';
require_once 'UserRelationDao.php';
require_once 'dao/PostDao.php';

class UserDao implements ud {

    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    private function generateUser($array, $full = false) {
        $u = new User();
        $u->id = $array['id'] ?? 0;
        $u->email = $array['email'] ?? '';
        $u->password = $array['password'] ?? '';
        $u->name = $array['name'] ?? '';
        $u->birthdate = $array['birthdate'] ?? '';
        $u->city = $array['city'] ?? '';
        $u->work = $array['work'] ?? '';
        $u->avatar = $array['avatar'] ?? 'default.jpg';
        $u->cover = $array['cover'] ?? '';
        $u->token = $array['token'] ?? '';

        if($full) {
            $userDao = new UserRelationDao($this->pdo);
            $postDao = new PostDao($this->pdo);

            // followers
            $u->followers = $userDao->getFollowers($u->id);
            foreach($u->followers as $key => $follower_id) {
                $newUser = $this->findById($follower_id);
                $u->followers[$key] = $newUser;
            }

            // following
            $u->following = $userDao->getFollowing($u->id);
            foreach($u->following as $key => $follower_id) {
                $newUser = $this->findById($follower_id);
                $u->following[$key] = $newUser;
            }

            // fotos
            $u->photos = $postDao->getPhotosFrom($u->id);
        }

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

    public function findByEmail($email) {
        if (!empty($email)) {
            $conn = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $conn->bindValue(':email', $email);
            $conn->execute();
            if ($conn->rowCount() > 0) {
                $data = $conn->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data);
                return $user;
            }
        } 

        return false;
    }

    public function findById($id, $full = false) {
        if (!empty($id)) {
            $conn = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
            $conn->bindValue(':id', $id);
            $conn->execute();
            
            if ($conn->rowCount() > 0) {
                $data = $conn->fetch(PDO::FETCH_ASSOC);
                $user = $this->generateUser($data, $full);
                return $user;
            }
        } 

        return false;
    }

    public function update(User $user) {
        $conn = $this->pdo->prepare("UPDATE users SET email = :email, password = :password, name = :name, birthdate = :birthdate, city = :city, work = :work, avatar = :avatar, cover = :cover, token = :token WHERE id = :id");
        $conn->bindValue(':email', $user->email);
        $conn->bindValue(':password', $user->password);
        $conn->bindValue(':name', $user->name);
        $conn->bindValue(':birthdate', $user->birthdate);
        $conn->bindValue(':city', $user->city);
        $conn->bindValue(':work', $user->work);
        $conn->bindValue(':avatar', $user->avatar);
        $conn->bindValue(':cover', $user->cover);
        $conn->bindValue(':token', $user->token);
        $conn->bindValue(':id', $user->id);
        $conn->execute();
        return true;
    }

    public function insert(User $user) {
        $conn = $this->pdo->prepare("INSERT INTO users (email, password, name, birthdate, token) VALUES (:email, :password, :name, :birthdate, :token)");
        $conn->bindValue(':email', $user->email);
        $conn->bindValue(':password', $user->password);
        $conn->bindValue(':name', $user->name);
        $conn->bindValue(':birthdate', $user->birthdate);
        $conn->bindValue(':token', $user->token);
        $conn->execute();
        return true;
    }

}