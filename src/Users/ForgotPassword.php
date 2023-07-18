<?php

namespace App\Users;

use App\Database\Database;
use App\Helpers\FlashMessage;
use App\Helpers\Helpers;
use App\Models\User;
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class ForgotPassword {

    private $pdo;
    private $email;
    private $token;

    public function __construct()
    {
        $this->pdo = Database::getPDO();
        $this->email = $_POST['email'] ?? '';
        $this->token = Helpers::generateToken();
    }

    // Handle the password reset request, validate the form inputs, send a password reset email if validation passes,
    // and set success or error messages
    public function sendPasswordResetEmail()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = $this->email;
        // Find user's informations from the database
        $user = $this->getUserByEmail($email);

        if ($user) {
            // Generate a password reset token
            $token = $this->token;

            // Save the token in the database for the user
            $this->saveTokenForUser($email, $token);

            // Send the email with PHPMailer
            $mail = new PHPMailer(true);
            
            try {
                // Looking for .env at the root directory
                $dotenv = Dotenv::createImmutable(dirname(__DIR__));
                $dotenv->load();

                // SMTP parameters
                $mail->setLanguage('fr');
                $mail->isSMTP();
                $mail->CharSet = 'UTF-8';
                $mail->Host = $_ENV['EMAIL_SMTP_SERVER'];
                $mail->SMTPAuth = $_ENV['SMTP_AUTH'];
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Username = $_ENV['EMAIL_USER'];
                $mail->Password = $_ENV['EMAIL_PASSWORD'];
                $mail->Port = $_ENV['PORT_SMTP_SERVER'];


                // Sender and recipient of the email
                $mail->setFrom($_ENV['EMAIL_FROM'], 'Lorem Ipsum');
                $mail->addAddress($email);

                // Content of the email
                $mail->isHTML(true);
                $mail->Subject = 'Réinitialisation de mot de passe';
                $mail->Body = "<div style=\"text-align: center;\">
                    <img src=\"https://zupimages.net/up/22/51/ju54.png\" alt=\"logo\" style=\"border-radius: 50%; width: 100px\">
                    </div>
                    <p style=\"text-align: center;\">Bonjour <strong>{$user->fullname}</strong>,</p>
                    <hr>
                    <p style=\"text-align: center;\">
                        Cliquez sur ce <a href=\"http://localhost:8081/verify?token-fp=$token\" style=\"text-decoration:none\"><strong>lien</strong></a> réinitialiser votre mot de passe !
                    </p>
                    <br>
                    <p style=\"overflow-wrap: break-word\">
                        Si le lien ne fonctionne pas, veuillez recopier ce lien dans votre navigateur :
                        <br>
                        http://localhost:8081/verify?token-fp=$token
                    </p>";

                $mail->send();

                FlashMessage::setFlashMessage("Vous devez d'abord valider votre adresse email !", 'info');
                header('Location: login');
                exit();            
            } catch (Exception $e) {
                echo 'Une erreur est survenue lors de l\'envoi de l\'e-mail : ' . $mail->ErrorInfo;
                FlashMessage::setFlashMessage("Erreur ! $mail->ErrorInfo", 'danger');
                header('Location: login');
                exit();
            }
        } else {
            FlashMessage::setFlashMessage("Utilisateur non trouvé ! ", 'danger');
            header('Location: login');
            exit();        
        }
    }}

    // Find an user from the database by its email
    protected function getUserByEmail($email): ?User
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $statement = $this->pdo->prepare($query);
        $statement->execute(['email' => $email]);
        $user = $statement->fetchObject(User::class);
        return $user;
    }

    // Save the password reset token for an user in the database
    private function saveTokenForUser($email, $token): void
    {
        $query = "UPDATE users SET token = :token WHERE email = :email";
        $statement = $this->pdo->prepare($query);
        $statement->bindParam(':token', $token);
        $statement->bindParam(':email', $email);
        $statement->execute();
    }
}