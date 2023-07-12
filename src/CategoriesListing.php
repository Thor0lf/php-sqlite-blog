<?php

namespace App;

use PDO;
use PDOException;

class CategoriesListing {
    private $error;
    private $currentPage;
    private $categoriesPerPage;
    private $offset;
    private $pdo;
    private $categories;
    private $totalCategories;
    private $totalPages;

    public function __construct($currentPage = 1, $categoriesPerPage = 10) {
        $this->currentPage = $currentPage;
        $this->categoriesPerPage = $categoriesPerPage;
        $this->offset = ($currentPage - 1) * $categoriesPerPage;

        try {
            $this->pdo = Database::getPDO();
            $query = $this->pdo->query("SELECT * FROM categories ORDER BY id DESC LIMIT $categoriesPerPage OFFSET $this->offset");
            $this->categories = $query->fetchAll(PDO::FETCH_OBJ);

            $result = $this->pdo->query("SELECT COUNT(*) as total FROM categories");
            $row = $result->fetch();
            $this->totalCategories = $row['total'];

            $this->totalPages = ceil($this->totalCategories / $this->categoriesPerPage);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    //Get all users
    public function getAllCategories() {
        return $this->categories;
    }

    //Get the number of categories
    public function getTotalCategories() {
        return $this->totalCategories;
    }

    //Get the number of pages
    public function getTotalPages() {
        return $this->totalPages;
    }

    // Return any error that occurred during form validation or processing.
    public function getError() {
        return $this->error;
    }
}