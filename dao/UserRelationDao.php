<?php 

require_once 'models/UserRelation.php';

class UserRelationDao implements u_rel {

    private $pdo;

    public function __construct(PDO $driver) {
        $this->pdo = $driver;
    }

    public function insert(UserRelation $u) {

    }

    public function getRelationsFrom($id) {
        $users = [$id];
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
}