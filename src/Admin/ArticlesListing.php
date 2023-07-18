<?php

namespace App\Admin;

use App\Database\Database;
use PDO;
use PDOException;

class ArticlesListing {
    private $error;
    private $currentPage;
    private $articlesPerPage;
    private $offset;
    private $pdo;
    private $articles;
    private $totalArticles;
    private $totalPages;

    public function __construct($currentPage = 1, $articlesPerPage = 10) {
        $this->currentPage = $currentPage;
        $this->articlesPerPage = $articlesPerPage;
        $this->offset = ($currentPage - 1) * $articlesPerPage;

        try {
            $this->pdo = Database::getPDO();
            $query = $this->pdo->query("SELECT * FROM articles ORDER BY id DESC LIMIT $articlesPerPage OFFSET $this->offset");
            $this->articles = $query->fetchAll(PDO::FETCH_OBJ);

            $result = $this->pdo->query("SELECT COUNT(*) as total FROM articles");
            $row = $result->fetch();
            $this->totalArticles = $row['total'];

            $this->totalPages = ceil($this->totalArticles / $this->articlesPerPage);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    // Get all users
    public function getAllArticles() {
        return $this->articles;
    }

    // Get the number of users
    public function getTotalArticles() {
        return $this->totalArticles;
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