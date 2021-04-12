<?php

class Post {
    public $id;
    public $id_user; // text // photo
    public $type;
    public $created_at;
    public $body;
}

interface PostD {
    public function insert(Post $p);
}