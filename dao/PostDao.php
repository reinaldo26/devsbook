<?php
require_once 'models/Post.php';

class PostDao implements PostD {
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function insert(Post $p) {
        $conn = $this->pdo->prepare("INSERT INTO posts (id_user, type, created_at, body) VALUES (:id_user, :type, :created_at, :body)");
        $conn->bindValue(':id_user', $p->id_user);
        $conn->bindValue(':type', $p->type);
        $conn->bindValue(':created_at', $p->created_at);
        $conn->bindValue(':body', $p->body);
        $conn->execute();
    }

}