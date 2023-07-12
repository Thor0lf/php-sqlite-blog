<?php

namespace App;

use PDO;
use PDOException;

class CommentsListing {
    private $error;
    private $currentPage;
    private $commentsPerPage;
    private $offset;
    private $pdo;
    private $comments;
    private $totalComments;
    private $totalPages;

    public function __construct($currentPage = 1, $commentsPerPage = 10) {
        $this->currentPage = $currentPage;
        $this->commentsPerPage = $commentsPerPage;
        $this->offset = ($currentPage - 1) * $commentsPerPage;

        try {
            $this->pdo = Database::getPDO();
            $query = $this->pdo->query("SELECT * FROM comments LIMIT $commentsPerPage OFFSET $this->offset");
            $this->comments = $query->fetchAll(PDO::FETCH_OBJ);

            $result = $this->pdo->query("SELECT COUNT(*) as total FROM comments");
            $row = $result->fetch();
            $this->totalComments = $row['total'];

            $this->totalPages = ceil($this->totalComments / $this->commentsPerPage);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    // Get all comments of an article
    public function getAllCommentsByArticle($articleId) {
        try {
            $query = $this->pdo->prepare("SELECT * FROM comments WHERE article_id = :article_id ORDER BY id DESC");
            $query->bindValue(':article_id', $articleId, PDO::PARAM_INT);
            $query->execute();
            $comments = $query->fetchAll(PDO::FETCH_OBJ);
            return $comments;
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return array();
        }
    }


    // Get the number of comments
    public function getTotalComments() {
        return $this->totalComments;
    }

    // Get the number of pages
    public function getTotalPages() {
        return $this->totalPages;
    }

    // Return any error that occurred during form validation
    public function getError() {
        return $this->error;
    }
}