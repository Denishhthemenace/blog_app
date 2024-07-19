<?php
class Post {
    private $conn;
    private $table_name = "posts";

    public $id;
    public $user_id;
    public $title;
    public $content;
    public $created_at;
    public $updated_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "SELECT p.*, u.username 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt;
    }

    public function readOne() {
        $query = "SELECT p.*, u.username 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.id = ? 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->title = $row['title'];
        $this->content = $row['content'];
        $this->user_id = $row['user_id'];
        $this->username = $row['username'];
        $this->created_at = $row['created_at'];
        $this->updated_at = $row['updated_at'];
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                  SET title=:title, content=:content, user_id=:user_id";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->user_id = htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":user_id", $this->user_id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET title = :title, content = :content
                  WHERE id = :id";

        $stmt = $this->conn->prepare($query);

        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(":title", $this->title);
        $stmt->bindParam(":content", $this->content);
        $stmt->bindParam(":id", $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(1, $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    public function search($keywords) {
        $query = "SELECT p.*, u.username 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  WHERE p.title LIKE ? OR p.content LIKE ? OR u.username LIKE ? 
                  ORDER BY p.created_at DESC";

        $stmt = $this->conn->prepare($query);

        $keywords = htmlspecialchars(strip_tags($keywords));
        $keywords = "%{$keywords}%";

        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);

        $stmt->execute();

        return $stmt;
    }

    public function readPaging($from_record_num, $records_per_page) {
        $query = "SELECT p.*, u.username 
                  FROM " . $this->table_name . " p 
                  LEFT JOIN users u ON p.user_id = u.id 
                  ORDER BY p.created_at DESC
                  LIMIT ?, ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt;
    }

    public function count() {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row['total_rows'];
    }
}