<?php

namespace App\Users;
use App\Database\Database;
use App\Helpers\FlashMessage;

class ModifyProfileUser {

    private $error;
    private $fullname;
    private $newEmail;
    private $oldEmail;
    private $database;
    private $updatedAt;

    public function __construct() 
    {
        $this->error = false;
        $this->fullname = $_POST['fullname'] ?? null;
        $this->newEmail = $_POST['email'] ?? null;
        $this->database = new Database();
        $this->updatedAt = date('Y-m-d H:i:s');
    }

    // Validate the form inputs for modifying the profile
    public function validateForm() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $count = $this->database->validateEmail($this->newEmail);
            $this->oldEmail = $this->database->getAuth()->user()->email;
            switch (true) {
                case empty($this->fullname) || empty($this->newEmail):
                    $this->error = "Un des champs est vide.";
                    break;
                case $count && $this->newEmail !== $this->oldEmail:
                    $this->error = "L'adresse email {$this->newEmail} est déjà utilisée !";
            }
        }   
    }

    // Process the form and modify the profile if validation passes and redirect the user
    public function processForm() 
    {
        if (!$this->error && isset($this->fullname) && isset($this->newEmail)) {
            $this->oldEmail = $this->database->getAuth()->user()->email;
            $this->database->modifyProfile($this->fullname, $this->newEmail, $this->oldEmail, $this->updatedAt);
            if ($this->oldEmail !== $this->newEmail) {
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_destroy();
                }
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                FlashMessage::setFlashMessage("Le profil a bien été modifié ! Veuillez vous reconnecter !", 'success');
                header("Location: /login");
                exit();
            }
            FlashMessage::setFlashMessage("Le profil a bien été modifié !", 'success');
            header('Location: profile');
            exit();
        }
    }

    //R eturn any error that occurred during form validation
    public function getError() {
        return $this->error;
    }
 
}