<?php

namespace App\Users;
use App\Database\Database;
use App\Helpers\FlashMessage;

class AddEditComment {

    private $database;
    private $error;
    private $referer;
    private $articleId;
    private $categoryId;
    private $id;
    private $deleteId;
    private $comment;
    private $userId;
    private $createdAt;
    private $updatedAt;



    public function __construct()
    {
        $this->database = new Database();
        $this->error = false;
        $this->referer = $_POST['referer'] ?? null;
        $this->articleId = $_GET['id'] ?? null;
        $this->categoryId = $this->database->getArticleById($this->articleId)->category_id ?? null;
        $this->id = $_POST['comment_id'] ?? null;
        $this->deleteId = $_GET['del'] ?? null;
        $this->comment = $_POST['comment'] ?? null;
        $this->userId = Database::getAuth()->user()->id ?? null;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    // Method to validate form data
    public function validateForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
            $commentExisting = $this->database->getCommentById($this->id);
            if ($commentExisting) {
                switch (true) {
                    case empty($this->comment):
                        $this->error = "Le commentaire est vide.";
                        break;           
                    case strlen($this->comment) < 3:
                        $this->error = "Le commentaire est trop court.";
                        break;
                }
            } else {
                switch (true) {
                    case empty($this->categoryId):
                        $this->error = "La catégorie n'a pu être récupérée.";
                        break;
                    case empty($this->articleId):
                        $this->error = "L'article est manquant.";
                        break; 
                    case empty($this->comment):
                        $this->error = "Le commentaire est vide.";
                        break;           
                    case strlen($this->comment) < 3:
                        $this->error = "Le titre est trop court.";
                        break;
                    case !$this->userId:
                        $this->error = "L'utilisteur n'a pas pu être trouvé.";
                        break;
                }
            }
        }
    }

    // Method to process form data
    public function processForm() 
    {
        if (!$this->error && isset($this->comment)) {
            $commentExisting = $this->database->getCommentById($this->id);
            if ($commentExisting) {
                $this->database->modifyComment($this->id, $this->comment, $this->updatedAt);
                FlashMessage::setFlashMessage("Le commentaire a bien été modifié !", 'success');
                header("Location: /article?id={$commentExisting->article_id}");
            } else {
                $this->database->createTableComments();
                $this->database->insertComment($this->comment, $this->userId, $this->articleId, $this->categoryId, $this->createdAt, $this->updatedAt);
                FlashMessage::setFlashMessage("Le commentaire a bien été créé.", 'success');
                header("Location: {$this->referer}");
            }
            exit();
        }
    }

    public function deleteComment($id)
    {
        $comment = $this->database->getCommentById($this->deleteId);
        if ($this->userId === $comment->user_id) {
            $this->database->deleteComment($this->deleteId);
            FlashMessage::setFlashMessage("Le commentaire a bien été supprimé.", 'success');
            header("Location: /article?id={$comment->article_id}");
            exit();
        } else {
            header("Location: /403");
            exit();
        }
    }

    // Return any error that occurred during form validation
    public function getError() 
    {
        return $this->error;
    }
}