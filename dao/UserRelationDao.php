<?php 

require_once 'models/UserRelation.php';

class UserRelationDao implements u_rel {

    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function insert(UserRelation $u) {

    }

    public function getFollowing($id) {
        $users = [];
        $conn = $this->pdo->prepare("SELECT user_to FROM user_relations WHERE user_from = :user_from");
        $conn->bindValue(":user_from", $id);
        $conn->execute();
        
        if($conn->rowCount() > 0){
            $data = $conn->fetchAll();
            foreach($data as $item){
                $users[] = $item["user_to"];
            }
        }

        return $users;
    }

    public function getFollowers($id) {
        $users = [];
        $conn = $this->pdo->prepare("SELECT user_from FROM user_relations WHERE user_to = :user_to");
        $conn->bindValue(":user_to", $id);
        $conn->execute();
        
        if($conn->rowCount() > 0){
            $data = $conn->fetchAll();
            foreach($data as $item){
                $users[] = $item["user_from"];
            }
        }

        return $users;
    }

}