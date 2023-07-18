<?php

namespace App\Users;

use App\Database\Database;
use App\Helpers\FlashMessage;
use PDO;

class VerifyUser {

    private $pdo;
    private $token;
    private $tokenFp;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
        $this->token = htmlentities($_GET['token']) ?? '';
        $this->tokenFp = htmlentities($_GET['token-fp']);
    }

    // Verifiy the user after registration by checking the token in the database, 
    // updating the token and 'isValid' field, and setting appropriate flash messages
    public function verifyUserAfterRegisterEmail(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
                        
        // Query to search for the user corresponding to the token
        $query = "SELECT * FROM users WHERE token = :token";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':token', $this->token);
        $statement->execute();
        
        // Check if a corresponding user is found
        if ($statement->fetch(PDO::FETCH_ASSOC)) {
            // Update the token and the 'isValid' input
            $updateQuery = "UPDATE users SET token = '', isValid = 1 WHERE token = :token";
            $updateStatement = $this->pdo->prepare($updateQuery);
            $updateStatement->bindParam(':token', $this->token);
            $updateStatement->execute();
        
            // Check if the update was successful
            if ($updateStatement->rowCount() > 0) {
                FlashMessage::setFlashMessage('Compte activé avec succès !', 'success');
                header('Location: /login');
                exit();
            } else {
                FlashMessage::setFlashMessage('Erreur lors de la mise à jour du token !', 'danger');
            }
        } else {
            FlashMessage::setFlashMessage('Aucun utilisateur correspondant au token trouvé.', 'danger');
        }
    }

    // Verify the user before password reset by checking the token in the database, 
    // updating the token, and setting appropriate flash messages
    public function verifyUserBeforeResetPassword() 
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Query to search for the user corresponding to the token
        $query = "SELECT * FROM users WHERE token = :token";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['token' => $this->tokenFp]);

        // Check if a corresponding user is found
        if ($statement->fetch(PDO::FETCH_ASSOC)) {
            //Update the token
            $updateQuery = "UPDATE users SET token = :token WHERE token = :token";
            $updateStatement = $this->pdo->prepare($updateQuery);
            $updateStatement->execute(['token' => $this->tokenFp]);

            // Check if the update was successful
            if ($updateStatement->rowCount() > 0) {
                $_SESSION['user_token'] = $this->tokenFp;
                header('Location: reset-password');
                exit();
            } else {
                FlashMessage::setFlashMessage('Erreur lors de la mise à jour du token !', 'danger');
                header('Location: login');
                exit();
            }
        } else {
            FlashMessage::setFlashMessage('Aucun utilisateur correspondant au token trouvé.', 'danger');
            header('Location: login');
            exit();
        }
    }
}