<?php

namespace App\Admin;

use App\Database\Database;
use App\Models\User;
use PDO;
use PDOException;

class UsersListing {
    private $error;
    private $currentPage;
    private $usersPerPage;
    private $offset;
    private $pdo;
    private $users;
    private $totalUsers;
    private $totalPages;

    public function __construct($currentPage = 1, $usersPerPage = 10) {
        $this->currentPage = $currentPage;
        $this->usersPerPage = $usersPerPage;
        $this->offset = ($currentPage - 1) * $usersPerPage;

        try {
            $this->pdo = Database::getPDO();
            $query = $this->pdo->query("SELECT * FROM users LIMIT $usersPerPage OFFSET $this->offset");
            $this->users = $query->fetchAll(PDO::FETCH_CLASS, User::class);

            $result = $this->pdo->query("SELECT COUNT(*) as total FROM users");
            $row = $result->fetch();
            $this->totalUsers = $row['total'];

            $this->totalPages = ceil($this->totalUsers / $this->usersPerPage);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }

    // Get all users
    public function getAllUsers() {
        return $this->users;
    }

    // Get the number of users
    public function getTotalUsers() {
        if ($this->totalUsers) {
            return $this->totalUsers;        
        } else {
            return 0;
        }
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