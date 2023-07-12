<?php

namespace App;
use PDO;

class AddEditArticle {

    private $database;
    private $error;
    private $categoryId;
    private $id;
    private $deleteId;
    private $title;
    private $content;
    private $formFile;
    private $metadata;
    private $userId;
    private $createdAt;
    private $updatedAt;



    public function __construct()
    {
        $this->database = new Database();
        $this->error = false;
        $this->categoryId = $_POST['category_id'] ?? null;
        $this->id = $_GET['id'] ?? null;
        $this->deleteId = $_GET['del'] ?? null;
        $this->title = $_POST['title'] ?? null;
        $this->content = $_POST['content'] ?? null;
        $file = $_FILES['formFile']['name'] ?? '';
        $this->formFile = uniqid() . '-' . $file;
        $this->metadata = $_POST['metadata'] ?? null;
        $this->userId = Database::getAuth()->user()->id ?? null;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    // Method to validate form data
    public function validateForm(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch (true) {
                case empty($this->categoryId):
                    $this->error = "La catégorie est manquante.";
                    break; 
                case empty($this->title) || empty($this->content) || empty($this->metadata):
                    $this->error = "L'un des champs est vide.";
                    break;           
                case strlen($this->title) < 3:
                    $this->error = "Le titre est trop court.";
                    break;
                case strlen($this->title) > 250:
                    $this->error = "Le titre est trop long.";
                    break;
                case strlen($this->content) < 3:
                    $this->error = "Le contenu est trop court.";
                    break;
                case strlen($this->metadata) < 3:
                    $this->error = "Le titre est trop court.";
                    break;
                case strlen($this->metadata) > 250:
                    $this->error = "Le titre est trop long.";
                    break;
                case !$this->userId:
                    $this->error = "L'utilisteur n'a pas pu être trouvé.";
                    break;
            }

            // Check if it is a modification of an article without changing the title image
            if ($this->id && empty($_FILES['formFile']['name'])) {
                $this->formFile = $this->getArticle()->formFile;
            } else {
                // Check the validation for the upload of the title image
                $limitSize = 5 * 1024 * 1024; // 5MB
                $extension = pathinfo($this->formFile, PATHINFO_EXTENSION);
                $extensionsAllowed = ['png', 'jpg', 'jpeg', 'webp'];
                switch (true) {
                    case !$_FILES['formFile']['name']:
                        $this->error = "L'image est manquante.";
                        break;
                    case $_FILES['formFile']['size'] > $limitSize:
                        $this->error = "L'image est trop volumineuse.";
                        break;
                    case !in_array($extension, $extensionsAllowed):
                        $this->error = "L'image n'a pas la bonne extension.";
                        break;
                }
            }
        }
    }

    // Method to process form data and perform file upload
    public function processForm() 
    {
            // Check if it is a modification of an article without changing the title image
            if ($this->id && empty($_FILES['formFile']['name'])) {
            $this->formFile = $this->getArticle()->formFile;
        } else {
            // Destination path for the title image
            $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'uploads';
            $uploadFile = $uploadDir . DIRECTORY_SEPARATOR . basename($this->formFile);
            if ($this->getArticle()) {
                $uploadedOldFile = $uploadDir . DIRECTORY_SEPARATOR . basename($this->getArticle()->formFile);
                // Delete the old title image
                if (file_exists($uploadedOldFile)) {
                    if (unlink($uploadedOldFile)) {
                        unlink($uploadedOldFile);
                    } else {
                        $this->error = "Une erreur s'est produite lors de la suppression de l'image.";
                    }
                } else {
                    $this->error = "L'image n'existe pas.";
                }
            }
            // Move the temp file to the final destination
            move_uploaded_file($_FILES['formFile']['tmp_name'] ?? '', $uploadFile);
        }
        if (!$this->error && isset($this->title, $this->content, $this->metadata, $this->userId, $this->categoryId, $this->createdAt, $this->updatedAt)) {
            if ($_GET['id']) {
                $this->database->modifyArticle($this->id, $this->title, $this->content, $this->formFile, $this->metadata, $this->userId, $this->categoryId, $this->updatedAt);
                FlashMessage::setFlashMessage("L'article [''$this->title''] a bien été modifié !", 'success');
            } else {
                $this->database->createTableArticles();
                $this->database->insertArticle($this->title, $this->content, $this->formFile,$this->metadata, $this->userId, $this->categoryId, $this->createdAt, $this->updatedAt);
                FlashMessage::setFlashMessage("L'article [''$this->title''] a bien été créé.", 'success');
            }
            header('Location: /articles-list');
            exit();
        }
    }

    public function getArticle()
    {
        return $this->database->getArticleById($this->id);
    }

    public function getCategory() 
    {
        return $this->database->getPDO()->query("SELECT id, name FROM categories")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTitle() 
    {
        return $this->title;
    }
    
    public function getContent() 
    {
        return $this->content;
    }
    
    public function getMetadata() 
    {
        return $this->metadata;
    }

    public function deleteArticle($id)
    {
        $article = $this->database->getArticleById($this->deleteId);
        if ($article) {
            $uploadDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'uploads';
            $uploadedOldFile = $uploadDir . DIRECTORY_SEPARATOR . basename($article->formFile);
            if (file_exists($uploadedOldFile)) {
                if (unlink($uploadedOldFile)) {
                } else {
                    $this->error = "Une erreur s'est produite lors de la suppression de l'image.";
                }
            } else {
                $this->error = "L'image n'existe pas.";
            }
        }
        $this->database->deleteArticle($this->deleteId);
        $this->database->deleteCommentsWithArticle($this->deleteId);
        $this->database->removeLikesWithArticle($this->deleteId);
        FlashMessage::setFlashMessage("L'article [''$article->title''] a bien été supprimé.", 'success');
        header('Location: /articles-list');
        exit();
    }

    // Return any error that occurred during form validation
    public function getError() 
    {
        return $this->error;
    }
}