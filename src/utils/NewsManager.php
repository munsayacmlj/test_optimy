<?php

namespace App\Utils;

use App\Classes\Interfaces\{ListInterface};
use App\Utils\{DB, CommentManager};
use App\Classes\News;

class NewsManager extends Manager implements ListInterface
{
    private static $instance = null;

    private function __construct()
    {
        // Set the table name and the class name
        parent::__construct('news', 'classes\News');
    }

    public static function getInstance()
    {
        if (null === self::$instance) {
            $c = __CLASS__;
            self::$instance = new $c();
        }
        return self::$instance;
    }

    /**
    * list all news
    */
    public function listAll()
    {
        // Converted to prepared statement to prevet SQL injection
        $sql = "SELECT * FROM " . $this->table;
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();

        $newsCollection = [];
        foreach ($data as $row) {
            $news = new News($row['id'], $row['title'], $row['body'], $row['created_at']);
            $newsCollection[] = $news;
        }

        return $newsCollection;
    }

    /**
    * add a record in news table
    */
    public function addNews($title, $body)
    {

        // Converted to prepared statement to prevet SQL injection
        $sql = "INSERT INTO `news` (`title`, `body`, `created_at`) VALUES(:title, :body, :created_at)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':body', $body);
        $stmt->bindParam(':created_at', date('Y-m-d'));
        $stmt->execute();

        return $this->db->lastInsertId();
    }

    /**
    * deletes a news, and also linked comments
    */
    public function deleteNews($id)
    {
        $comments = CommentManager::getInstance()->listAll();
        $idsToDelete = [];

        foreach ($comments as $comment) {
            if ($comment->getNewsId() == $id) {
                $idsToDelete[] = $comment->getId();
            }
        }

        /**
         * This is the guarantee atomicity.
         *
         * Using a transaction to ensure that all the operations are executed
         * or none of them are executed.
         *
         * This is to prevent the database from being in an inconsistent state
         * if an error occurs during the deletion of the news and its comments.
         */
        $this->db->beginTransaction();
        try {
            foreach ($idsToDelete as $id) {
                CommentManager::getInstance()->deleteComment($id);
            }

            // Converted to prepared statement to prevet SQL injection
            $sql = "DELETE FROM `news` WHERE `id`=:id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id);

            $this->db->commit();
            return $stmt->execute();
        } catch (\Exception $e) {
            $this->db->rollBack();
            // Log the error
            error_log($e->getMessage());
            return false;
        }
    }
}
