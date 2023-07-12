<?php

namespace App;

use PDO;
use PDOException;

class Database {

    public static $pdo;
    public static $auth;
    public $error;

    // Return a PDO instance for database interaction
    public static function getPDO(): PDO
    {
        if (!self::$pdo) {
            self::$pdo = new PDO("sqlite:../data.db", null, null, [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$pdo;
    }

    // Return an Auth instance for user authentication
    public static function getAuth(): Auth
    {
        if (!self::$auth) {
            self::$auth = new Auth(self::getPDO(), '/login');
        }
        return self::$auth;
    }

    // Create the table 'users' if it did not exist in the database
    public function createTableUsers(): void
    {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS users (
            id TEXT PRIMARY KEY NOT NULL UNIQUE,
            fullname TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            role TEXT NOT NULL DEFAULT 'user',
            isValid INTEGER NOT NULL DEFAULT 0,
            token TEXT,
            created_at INTEGER NOT NULL,
            updated_at INTEGER NOT NULL
        )");
    }

    // Insert a new user into the database
    public function insertUser($fullname, $email, $password, $token, $role, $createdAt, $updatedAt): void
    {
        $query = self::$pdo->prepare('INSERT INTO users (id, fullname, email, password, role, token , created_at, updated_at) VALUES (:id, :fullname, :email, :password, :role, :token, :created_at, :updated_at)');
        $query->execute([
            'id'         => uniqid(),
            'fullname'   => $fullname,
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'token'      => $token,
            'role'       => $role,
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ]);
    }

    // Get the user from the database by its ID
    public function getUserById($userId): ?User
    {    
        $query = self::$pdo->prepare("SELECT * FROM users WHERE id = :id");
        $query->bindParam(':id', $userId);
        $query->execute();
        $user = $query->fetchObject(User::class);
        
        if ($user === false) {
            return null;
        }
        return  $user;
    }

    // Reset an user's password
    public function resetPassword($password, $token, $updatedAt): void
    {
        $query = self::$pdo->prepare('UPDATE users SET password = :password, token = null, updated_at = :updated_at WHERE token = :token');
        $query->execute([
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'token'      => $token,
            'updated_at' => $updatedAt
        ]);
    }

    // Modify an user's password
    public function changePassword($email, $password, $updatedAt): void
    {
        $query = self::$pdo->prepare('UPDATE users SET password = :password, updated_at = :updated_at WHERE email = :email');
        $query->execute([
            'email'      => $email,
            'password'   => password_hash($password, PASSWORD_DEFAULT),
            'updated_at' => $updatedAt
        ]);
    }

    // Modify an user's profile
    public function modifyProfile($fullname, $newEmail, $oldEmail, $updatedAt): void
    {
        $query = self::$pdo->prepare('UPDATE users SET fullname = :fullname, email = :new_email, updated_at = :updated_at WHERE email = :old_email');
        $query->execute([
            'fullname'   => $fullname,
            'new_email'  => $newEmail,
            'old_email'  => $oldEmail,
            'updated_at' => $updatedAt
        ]);
    }

    // Validate an user's email by checking if the same email address is in the database
    public function validateEmail($newEmail): bool
    {
        $query = self::$pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :new_email");
        $query->execute(['new_email' => $newEmail]);
        return $query->fetchColumn();
    }

    // Delete an user from the database
    public function deleteUser($email): void
    {
        $query = self::$pdo->prepare('DELETE FROM users WHERE email = :email');
        $query->execute([
            'email'  => $email,
        ]);
    }

    // Create the table 'categories' if it did not exist in the database
    public function createTableCategories(): void
    {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name TEXT NOT NULL,
            created_at INTEGER NOT NULL,
            updated_at INTEGER NOT NULL
        )");
    }

    // Insert a new category into the database
    public function insertCategory($name, $createdAt, $updatedAt): void
    {
        $query = self::$pdo->prepare("INSERT INTO categories (name, created_at, updated_at) VALUES (?, :created_at, :updated_at)");
        $query->execute([
            $name, 
            'created_at' => $createdAt,
            'updated_at' => $updatedAt
        ]);
    }

    // Get a category from the database by its ID
    public function getCategoryById($categoryId): ?Category
    {    
        $query = self::$pdo->prepare("SELECT * FROM categories WHERE id = :id");
        $query->bindParam(':id', $categoryId);
        $query->execute();
        $category = $query->fetchObject(Category::class);
        
        if ($category === false) {
            return null;
        }
        return  $category;
    }

    // Get the count of the articles of a category
    public function getArticlesCountByCategory($categoryId) {
        try {
            $this->createTableArticles();
            $query = self::$pdo->prepare("SELECT COUNT(*) as total FROM articles WHERE category_id = :category_id");
            $query->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();
            return $row['total'];
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }

    // Modify a category
    public function modifyCategory($id, $name, $updatedAt): void
    {
        $query = self::$pdo->prepare('UPDATE categories SET name = :name, updated_at = :updated_at WHERE id = :id');
        $query->execute([
            'id'         => $id,
            'name'       => $name,
            'updated_at' => $updatedAt
        ]);
    }

    // Delete a category
    public function deleteCategory($id): void
    {
        $query = self::$pdo->prepare('DELETE FROM categories WHERE id = :id');
        $query->execute([
            'id'  => $id,
        ]);
    }

    // Create the table 'articles' if it did not exist in the database
    public function createTableArticles(): void
    {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS articles (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            title TEXT NOT NULL,
            formFile TEXT NOT NULL,
            content TEXT NOT NULL,
            user_id INTEGER NOT NULL,
            metadata TEXT NOT NULL,
            category_id TEXT NOT NULL,
            created_at INTEGER NOT NULL,
            updated_at INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )");
    }

    // Insert a new article into the database
    public function insertArticle($title, $content, $metadata, $formFile, $userId, $categoryId, $createdAt, $updatedAt): void
    {
        $query = self::$pdo->prepare("INSERT INTO articles (title, content, formFile, metadata, user_id, category_id, created_at, updated_at) VALUES (?, ?, ?, ?, :user_id, :category_id, :created_at, :updated_at)");
        $query->execute([
            $title, 
            $content, 
            $metadata, 
            $formFile, 
            'user_id'     => $userId,
            'category_id' => $categoryId,
            'created_at'  => $createdAt,
            'updated_at'  => $updatedAt
        ]);
    }

    // Get an article from the database by its ID
    public function getArticleById($articleId): ?Article
    {    
        $query = self::$pdo->prepare("SELECT * FROM articles WHERE id = :id");
        $query->bindParam(':id', $articleId);
        $query->execute();
        $article = $query->fetchObject(Article::class);
        
        if ($article === false) {
            return null;
        }
        return  $article;
    }

    // Modify an article
    public function modifyArticle($id, $title, $content, $formFile, $metadata, $userId, $categoryId, $updatedAt): void
    {
        $query = self::$pdo->prepare('UPDATE articles SET title = :title, content = :content, formFile = :formFile, metadata = :metadata, user_id = :user_id, category_id = :category_id, updated_at = :updated_at WHERE id = :id');
        $query->execute([
            'title'       => $title,
            'content'     => $content,
            'formFile'    => $formFile,
            'metadata'    => $metadata,
            'user_id'     => $userId,
            'category_id' => $categoryId,
            'updated_at'  => $updatedAt,
            'id'          => $id
        ]);
    }

    // Delete an article
    public function deleteArticle($id): void
    {
        $query = self::$pdo->prepare('DELETE FROM articles WHERE id = :id');
        $query->execute([
            'id'  => $id,
        ]);
    }

    // Delete articles with Category 
    public function deleteArticleWithCategory($categoryId): void
    {
        $query = self::$pdo->prepare('DELETE FROM articles WHERE category_id = :category_id');
        $query->execute([
            ':category_id'  => $categoryId,
        ]);
    }

    // Create the table 'comments' if it did not exist in the database
    public function createTableComments(): void
    {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS comments (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            comment TEXT NOT NULL,
            user_id INTEGER NOT NULL,
            article_id INTEGER NOT NULL,
            category_id TEXT NOT NULL,
            created_at INTEGER NOT NULL,
            updated_at INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (article_id) REFERENCES articles(id)
        )");
    }

    // Insert a new comment into the database
    public function insertComment($comment, $userId, $articleId, $categoryId, $createdAt, $updatedAt): void
    {
        $query = self::$pdo->prepare("INSERT INTO comments (comment, user_id, article_id, category_id, created_at, updated_at) VALUES (?, :user_id, :article_id, :category_id, :created_at, :updated_at)");
        $query->execute([
            $comment,
            'user_id'     => $userId,
            'article_id'  => $articleId,
            'category_id' => $categoryId,
            'created_at'  => $createdAt,
            'updated_at'  => $updatedAt
        ]);
    }
 
    // Get a comment from the database by its ID
    public function getCommentById($commentId): ?Comment
    {    
        $this->createTableComments();
        $query = self::$pdo->prepare("SELECT * FROM comments WHERE id = :id");
        $query->bindParam(':id', $commentId);
        $query->execute();
        $comment = $query->fetchObject(Comment::class);
        
        if ($comment === false) {
            return null;
        }
        return  $comment;
    }

    // Get the count of the comments of an article
    public function getCommentsCountByArticle($articleId) {
        try {
            $this->createTableComments();
            $query = self::$pdo->prepare("SELECT COUNT(*) as total FROM comments WHERE article_id = :article_id");
            $query->bindParam(':article_id', $articleId, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();
            return $row['total'];
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }

    // Modify a comment
    public function modifyComment($id, $comment, $updatedAt): void
    {
        $query = self::$pdo->prepare('UPDATE comments SET comment = :comment, updated_at = :updated_at WHERE id = :id');
        $query->execute([
            'id'         => $id,
            'comment'    => $comment,
            'updated_at' => $updatedAt
        ]);
    }

    // Delete a comment
    public function deleteComment($id): void
    {
        $query = self::$pdo->prepare('DELETE FROM comments WHERE id = :id');
        $query->execute([
            'id'  => $id,
        ]);
    }

    // Delete comments with category
    public function deleteCommentsWithCategory($categoryId): void
    {
        $query = self::$pdo->prepare('DELETE FROM comments WHERE category_id = :category_id');
        $query->execute([
            ':category_id'  => $categoryId,
        ]);
    }

    // Delete comments with article
    public function deleteCommentsWithArticle($articleId): void
    {
        $query = self::$pdo->prepare('DELETE FROM comments WHERE article_id = :article_id');
        $query->execute([
            ':article_id'  => $articleId,
        ]);
    }    

    // Create the table 'likes' if it did not exist in the database
    public function createTableLikes(): void
    {
        self::$pdo->exec("CREATE TABLE IF NOT EXISTS likes (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            user_id INTEGER NOT NULL,
            article_id INTEGER NOT NULL,
            category_id INTEGER NOT NULL,
            created_at INTEGER NOT NULL,
            FOREIGN KEY (user_id) REFERENCES users(id),
            FOREIGN KEY (article_id) REFERENCES articles(id)
        )");
    }

    // Insert a new like into the database
    public function addLike($userId, $articleId, $categoryId, $createdAt)
    {
        $query = self::$pdo->prepare("INSERT INTO likes (user_id, article_id, category_id, created_at) VALUES (:user_id, :article_id, :category_id, :created_at)");
        $query->execute([
            ':user_id'     => $userId,
            ':article_id'  => $articleId,
            ':category_id' => $categoryId,
            ':created_at'  => $createdAt
        ]);
    }

    // Remove a like
    public function removeLike($userId, $articleId): void
    {
        $query = self::$pdo->prepare("DELETE FROM likes WHERE article_id = :article_id AND user_id = :user_id");
        $query->execute([
            ':user_id'    => $userId,
            ':article_id' => $articleId
        ]);
    }

    // Remove likes with deletion of a category
    public function removeLikesWithCategory($categoryId): void
    {
        $query = self::$pdo->prepare("DELETE FROM likes WHERE category_id = :category_id");
        $query->execute([
            ':category_id' => $categoryId
        ]);
    }

    // Remove likes with deletion of an article
    public function removeLikesWithArticle($articleId): void
    {
        $query = self::$pdo->prepare("DELETE FROM likes WHERE article_id = :article_id");
        $query->execute([
            ':article_id' => $articleId
        ]);
    }

    // Get the count of likes for a given article
    public function getLikesCountByArticle($articleId) {
        try {
            $this->createTableLikes();
            $query = self::$pdo->prepare("SELECT COUNT(*) as total FROM likes WHERE article_id = :article_id");
            $query->bindParam(':article_id', $articleId, PDO::PARAM_INT);
            $query->execute();
            $row = $query->fetch();
            return $row['total'];
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            return null;
        }
    }
}

?>