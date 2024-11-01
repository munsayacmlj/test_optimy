<?php

namespace App\Utils;

use App\Classes\Interfaces\{ListInterface};
use App\Classes\Comment;

class CommentManager extends Manager implements ListInterface
{
    private static $instance = null;

    private function __construct()
    {
        // Set the table name and the class name
        parent::__construct('comment', 'classes\Comment');
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            $class = __CLASS__;
            self::$instance = new $class();
        }
        return self::$instance;
    }

    public function listAll()
    {
        // Converted to prepared statement to prevet SQL injection
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $commentsColection = [];
        foreach ($data as $row) {
            $comment = new Comment($row['id'], $row['body'], $row['created_at'], $row['news_id']);
            $commentsColection[] = $comment;
        }

        return $commentsColection;
    }

    public function addCommentForNews($body, $newsId)
    {
        // Converted to prepared statement to prevet SQL injection
        $sql = "INSERT INTO `comment` (`body`, `created_at`, `news_id`) VALUES(:body, :created_at, :news_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':created_at', date('Y-m-d'));
        $stmt->bindParam(':news_id', $newsId);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    public function deleteComment($id)
    {
        // Converted to prepared statement to prevet SQL injection
        $sql = "DELETE FROM `comment` WHERE `id`=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
