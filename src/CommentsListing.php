<?php

namespace App;

use App\Database\Database;
use PDO;
use PDOException;

class CommentsListing {
    private $error;
    private $currentPage;
    private $commentsPerPage;
    private $offset;
    private $pdo;
    private $id;
    private $comments;
    private $totalComments;
    private $totalPages;

    public function __construct($currentPage = 1, $commentsPerPage = 10) {
        $this->currentPage = $currentPage;
        $this->commentsPerPage = $commentsPerPage;
        $this->offset = ($currentPage - 1) * $commentsPerPage;
        $this->id = $_GET['id'] ?? null;

        try {
            $this->pdo = Database::getPDO();
            $query = $this->pdo->query("SELECT * FROM comments WHERE article_id = $this->id ORDER BY id DESC LIMIT $commentsPerPage OFFSET $this->offset");
            $this->comments = $query->fetchAll(PDO::FETCH_OBJ);

            $result = $this->pdo->query("SELECT COUNT(*) as total FROM comments WHERE article_id = $this->id");
            $row = $result->fetch();
            $this->totalComments = $row['total'];

            $this->totalPages = floor(($this->totalComments / $this->commentsPerPage) + 1);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    // Get all comments of an article
    public function getAllComments() {
        return $this->comments;
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