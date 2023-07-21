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
    private $categoryId;
    private $articles;
    private $totalArticles;
    private $totalPages;

    public function __construct($currentPage = 1, $articlesPerPage = 10, $category = null)
    {
        $this->currentPage = $currentPage;
        $this->articlesPerPage = $articlesPerPage;
        $this->offset = ($currentPage - 1) * $articlesPerPage;
        $this->categoryId = $_GET['category'] ?? null;
    
        // Check if the value is empty, and redirect to the same URL without the 'category' parameter
        if ($this->categoryId === '') {
            unset($_GET['category']);
            $newUrl = strtok($_SERVER["REQUEST_URI"], '?');
            if (!empty($_GET)) {
                $newUrl .= '?' . http_build_query($_GET);
            }
    
            header('Location: ' . $newUrl);
            exit;
        }

        try {
            $this->pdo = Database::getPDO();
            $sql = "SELECT * FROM articles";
            
            // If a category is specified, add a WHERE clause to your SQL query
            if ($this->categoryId) {
                $sql .= " WHERE category_id = :category";
            }

            $sql .= " ORDER BY id DESC LIMIT $articlesPerPage OFFSET $this->offset";
            $stmt = $this->pdo->prepare($sql);

            // If a category is specified, bind the category parameter
            if ($this->categoryId) {
                $stmt->execute([':category' => $this->categoryId]);
            } else {
                $stmt->execute();
            }

            $this->articles = $stmt->fetchAll(PDO::FETCH_OBJ);

            // Modify the count query to include the category filter
            $countSql = "SELECT COUNT(*) as total FROM articles";
            if ($category) {
                $countSql .= " WHERE category_id = :category";
            }
            $countStmt = $this->pdo->prepare($countSql);

            // If a category is specified, bind the category parameter
            if ($category) {
                $countStmt->execute([':category' => $category]);
            } else {
                $countStmt->execute();
            }

            $row = $countStmt->fetch();
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