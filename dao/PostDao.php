<?php
require_once 'models/Post.php';
require_once 'dao/UserRelationDao.php';
require_once 'dao/UserDao.php';

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

    public function getHomeFeed($id_user) {
        $array = [];
        // lista dos usuarios que o usuario segue
        $usuarioDao = new UserRelationDao($this->pdo);
        $userList = $usuarioDao->getRelationsFrom($id_user);
        $userList = implode(",", $userList);

        // pega os posts ordenados pela data
        $conn = $this->pdo->query("SELECT * FROM posts WHERE id_user IN ($userList) ORDER BY created_at DESC");

        if($conn->rowCount() > 0) {
            $data = $conn->fetchAll(PDO::FETCH_ASSOC);

            // transforma o resiltado em objetos
            $array = $this->_postListToObject($data, $id_user);
        }

        return $array;
    }

    private function _postListToObject($postList, $id_user) {
        $posts = [];
        $userDao = new UserDao($this->pdo);

        foreach($postList as $post_item) {
            $newPost = new Post();
            $newPost->id = $post_item['id'];
            $newPost->type = $post_item['type'];
            $newPost->created_at = $post_item['created_at'];
            $newPost->body = $post_item['body'];
            $newPost->mine = false;

            if($post_item['id_user'] == $id_user){
                $newPost->mine = true;
            }

            // pega informações adicionais do usuário
            $newPost->user = $userDao->findById($post_item['id_user']);

            // likes
            $newPost->likeCount = 0;
            $newPost->liked = false;

            // comments
            $newPost->comments = [];

            $posts[] = $newPost;
        }

        return $posts;
    }

}