<?php

namespace App\Users;
use App\Database\Database;
use App\Helpers\FlashMessage;

class DeleteUser {

    private $database;
    private $error;

    public function __construct()
    {
        $this->database = new Database();
        $this->error = false;
    }

    // Process the form and delete the user if the email matches the session email and redirect the user.
    public function processForm(): void
    {
        $email = $this->database->getAuth()->user()->email;
        $emailUserSession = $_SESSION['auth_email'];
        if ($email !== $emailUserSession) {
            FlashMessage::setFlashMessage("L'utilisateur n'a pas été trouvé.", 'danger');
            header("Location: /profile");
            exit();
        }
        $this->database->deleteUser($email);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        FlashMessage::setFlashMessage("L'utilisateur $email a bien été supprimé !", 'success');
        header("Location: /");
        exit();
    } 

        // Process the form and delete the user by the admin.
        public function processFormByAdmin(): void
        {
            $email = $_GET['email'];
            $role = $this->database->getAuth()->user()->role;
            if ($role === 'admin') {
                $this->database->deleteUser($email);
                FlashMessage::setFlashMessage("L'utilisateur $email a bien été supprimé !", 'success');
                header('Location: /users-list');
                exit();
            }
            FlashMessage::setFlashMessage("L'utilisateur n'a pas été trouvé.", 'danger');
            header('Location: /users-list');
            exit();
        }
}