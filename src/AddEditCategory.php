<?php


namespace App;

class AddEditCategory {

    private $database;
    private $error;
    private $id;
    private $deleteId;
    private $name;
    private $createdAt;
    private $updatedAt;



    public function __construct()
    {
        $this->database = new Database();
        $this->error = false;
        $this->id = $_GET['id'] ?? null;
        $this->deleteId = $_GET['del'] ?? null;
        $this->name = $_POST['name'] ?? null;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    // Method to validate form data
    public function validateForm(): void
    {
        $this->database->createTableArticles();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch (true) {
                case empty($this->name):
                    $this->error = "La catégorie doit avoir un nom.";
                    break;           
                case strlen($this->name) < 3:
                    $this->error = "Le nom de la catégorie est trop court.";
                    break;
                case strlen($this->name) > 250:
                    $this->error = "Le nom de la catégorie est trop long.";
                    break;
            }
        }
    }

    // Method to process form data
    public function processForm() 
    {
        if (!$this->error && isset($this->name)) {
            if ($_GET['id']) {
                $this->database->modifyCategory($this->id, $this->name, $this->updatedAt);
                FlashMessage::setFlashMessage("La catégorie a bien été modifiée !", 'success');
            } else {
                $this->database->createTableCategories();
                $this->database->insertCategory($this->name, $this->createdAt, $this->updatedAt);
                FlashMessage::setFlashMessage("La catégorie [''$this->name''] a bien été créé.", 'success');
            }
            header('Location: /categories-list');
            exit();
        }
    }

    public function getCategory()
    {
        return $this->database->getCategoryById($this->id);
    }
    
    public function getName() {
        return $this->name;
    }

    public function deleteCategory($id)
    {
        $category = $this->database->getCategoryById($this->deleteId);
        $this->database->deleteCategory($this->deleteId);
        $this->database->deleteArticleWithCategory($this->deleteId);
        $this->database->deleteCommentsWithCategory($this->deleteId);
        $this->database->removeLikesWithCategory($this->deleteId);
        FlashMessage::setFlashMessage("L'article [''$category->name''] a bien été supprimé.", 'success');
        header('Location: /categories-list');
        exit();
    }


    // Return any error that occurred during form validation
    public function getError() {
        return $this->error;
    }

}