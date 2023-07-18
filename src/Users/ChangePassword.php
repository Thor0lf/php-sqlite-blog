<?php

namespace App\Users;
use App\Database\Database;
use App\Helpers\FlashMessage;

class ChangePassword extends ForgotPassword {

    private $error;
    private $actualPassword;
    private $newPassword;
    private $confirmNewPassword;
    private $database;
    private $updatedAt;

    public function __construct() 
    {
        $this->error = false;
        $this->actualPassword = $_POST['actual_password'] ?? null;
        $this->newPassword = $_POST['password'] ?? null;
        $this->confirmNewPassword = $_POST['confirm_password'] ?? null;
        $this->database = new Database();
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    // Validate the form inputs for changing password.
    public function validateForm() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->database->getAuth()->user()->email;
            $query = new ForgotPassword();
            $user = $query->getUserByEmail($email);
            switch (true) {
                case empty($this->actualPassword) || empty($this->newPassword) || empty($this->confirmNewPassword):
                    $this->error = "Un des champs est vide.";
                    break;
                case !password_verify($this->actualPassword, $user->password):
                    $this->error = "Le mot de passe actuel est incorrect.";
                    break;
                case password_verify($this->newPassword, $user->password):
                    $this->error = "Le nouveau mot de passe doit être différent de l'ancien.";
                    break;
                case $this->newPassword !== $this->confirmNewPassword:
                    $this->error = "Les mots de passe ne correspondent pas.";
                    break;
                case strlen($this->newPassword) < 8:
                    $this->error = "Le mot de passe est trop court";
                    break;
                case !preg_match('/[A-Z]/', $this->newPassword):
                    $this->error = "Le mot de passe doit contenir au moins une majuscule";
                    break;
                case !preg_match('/[a-z]/', $this->newPassword):
                    $this->error = "Le mot de passe doit contenir au moins une minuscule";
                    break;
                case !preg_match('/[0-9]/', $this->newPassword):
                    $this->error = "Le mot de passe doit contenir au moins un chiffre";
                    break;
                case !preg_match('/[!@#$%^&*()\-_=+.]/', $this->newPassword):
                    $this->error = "Le mot de passe doit contenir au moins un symbole";
                    break;
            }
        }
    }

    // Validate the form inputs for changing password.
    public function processForm() 
    {
        if (!$this->error && isset($this->actualPassword, $this->newPassword, $this->confirmNewPassword)) {
            $email = $this->database->getAuth()->user()->email;
            $this->database->changePassword($email, $this->newPassword, $this->updatedAt);
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            }
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            FlashMessage::setFlashMessage("Le mot de passe a bien été modifié ! Veuillez vous reconnecter.", 'info');
            header('Location: /login');
            exit();
        }
    }

    // Return any error that occurred during form validation or processing.
    public function getError() {
        return $this->error;
    }
}