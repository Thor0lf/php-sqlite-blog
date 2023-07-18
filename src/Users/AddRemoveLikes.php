<?php

namespace App\Users;
use App\Database\Database;
use PDOException;

Class AddRemoveLikes {

    private $database;
    private $pdo;
    private $error;
    private $userId;
    private $articleId;
    private $categoryId;
    private $createdAt;
    private $totalLikesByArticle;
    private $likeByUser;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
        $this->database = new Database();
        $this->error = false;
        $this->userId = Database::getAuth()->user()->id ?? null;
        $this->articleId = $_GET['id'] ?? null;
        $this->categoryId = $this->database->getArticleById($this->articleId)->category_id ?? null;
        $this->createdAt = date('Y-m-d H:i:s');

        try {   
            $this->database->createTableLikes();     
            $numberOfLikesByArticle = $this->pdo->query("SELECT COUNT(*) as total FROM likes WHERE article_id = :article_id");
            $numberOfLikesByArticle->execute([
                ':article_id' => $this->articleId
            ]);
            $row = $numberOfLikesByArticle->fetch();
            $this->totalLikesByArticle = $row['total'];

            $likeByUser = $this->pdo->query("SELECT COUNT(*) as total FROM likes WHERE article_id = :article_id AND user_id = :user_id");
            $likeByUser->execute([
                ':user_id'    => $this->userId,
                ':article_id' => $this->articleId
            ]);
            $rowForLikeByUser = $likeByUser->fetch();
            $this->likeByUser = $rowForLikeByUser['total'];
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
        }
    }
    
    // Method to process form data
    public function ProcessForm()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['like'])) {
            // Check if user already liked the article in the database
            if ($this->getLikeByUser()) {
                // if it was liked, it will be unlike 
                $this->database->removeLike($this->userId, $this->articleId);
            } else {
                // Create the table if it is not created and store the like of the article in the database 
                $this->database->createTableLikes();
                $this->database->addLike($this->userId, $this->articleId, $this->categoryId, $this->createdAt);
            }
            header("Location: /article?id={$this->articleId}");
            exit();
        }
    }
    
    // To display the total number of likes for a given article
    function getTotalLikes() {
        return $this->totalLikesByArticle;
    }

    // Check if user already liked the article
    function getLikeByUser() {
        return $this->likeByUser;
    }

    // Return any error that occurred during form validation
    public function getError() 
    {
        return $this->error;
    }

}
?>