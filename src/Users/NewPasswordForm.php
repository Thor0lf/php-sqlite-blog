<?php 

namespace App\Users;
use App\Database\Database;
use App\Helpers\FlashMessage;


class NewPasswordForm {
    private $error;
    private $password;
    private $confirmPassword;
    private $token;
    private $database;
    private $updatedAt;

    public function __construct() 
    {
        $this->error = false;
        $this->password = $_POST['password'] ?? null;
        $this->confirmPassword = $_POST['confirm_password'] ?? null;
        $this->database = new Database();
        $this->updatedAt = date('Y-m-d H:i:s');
        $this->token = htmlentities($_SESSION['user_token']);

    }

    // Validate the form inputs for the new password
    public function validateForm() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch (true) {
                case empty($this->password) || empty($this->confirmPassword):
                    $this->error = "Un des champs est vide.";
                    break;
                case $this->password !== $this->confirmPassword:
                    $this->error = "Les mots de passe ne correspondent pas.";
                    break;
                case strlen($this->password) < 8:
                    $this->error = "Le mot de passe est trop court";
                    break;
                case !preg_match('/[A-Z]/', $this->password):
                    $this->error = "Le mot de passe doit contenir au moins une majuscule";
                    break;
                case !preg_match('/[a-z]/', $this->password):
                    $this->error = "Le mot de passe doit contenir au moins une minuscule";
                    break;
                case !preg_match('/[0-9]/', $this->password):
                    $this->error = "Le mot de passe doit contenir au moins un chiffre";
                    break;
                case !preg_match('/[!@#$%^&*()\-_=+.]/', $this->password):
                    $this->error = "Le mot de passe doit contenir au moins un symbole";
                    break;
            }
        }
    }

    // Process the form and reset the password if validation passes and redirect the user
    public function processForm() 
    {
        if (!$this->error && isset($this->password)) {
            $this->database->resetPassword($this->password, $this->token, $this->updatedAt);
            FlashMessage::setFlashMessage("Le mot de passe a bien été modifié !", 'success');
            unset($_SESSION['user_token']);
            header('Location: login');
            exit();
        }
    }

    // Return any error that occurred during form validation
    public function getError() {
        return $this->error;
    }
}