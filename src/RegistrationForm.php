<?php 

namespace App;

use App\Database;
use App\Functions;
use Dotenv\Dotenv;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class RegistrationForm {
    private $error;
    private $fullname;
    private $email;
    private $password;
    private $confirmPassword;
    private $token;
    private $role;
    private $database;
    private $createdAt;
    private $updatedAt;

    public function __construct() 
    {
        $this->error = false;
        $this->fullname = $_POST['fullname'] ?? null;
        $this->email = $_POST['email'] ?? '';
        $this->password = $_POST['password'] ?? null;
        $this->confirmPassword = $_POST['confirm_password'] ?? null;
        $this->database = new Database();
        $this->token = Functions::generateToken();
        $this->role;
        $this->createdAt = date('Y-m-d H:i:s');
        $this->updatedAt = $this->createdAt;
    }

    // Validate the form inputs for registration
    public function validateForm() 
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            switch (true) {
                case empty($this->fullname) || empty($this->email) || empty($this->password) || empty($this->confirmPassword):
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

    // Send a verification email to the user
    public function handleRegistrationFormSubmission() 
    {
        $mail = new PHPMailer(true);

        try {
          // Looking for .env at the root directory
          $dotenv = Dotenv::createImmutable(dirname(__DIR__));
          $dotenv->load();

          // SMTP Parameters
          $mail->setLanguage('fr');
          $mail->isSMTP();
          $mail->CharSet = 'UTF-8';
          $mail->Host = $_ENV['EMAIL_SMTP_SERVER'];
          $mail->SMTPAuth = $_ENV['SMTP_AUTH'];
          $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
          $mail->Username = $_ENV['EMAIL_USER'];
          $mail->Password = $_ENV['EMAIL_PASSWORD'];
          $mail->Port = $_ENV['PORT_SMTP_SERVER'];

          // Email parameters
          $mail->setFrom($_ENV['EMAIL_FROM'], 'Lorem Ipsum');
          $mail->addAddress($this->email);
          $mail->Subject = 'Validation de votre adresse email';
          $mail->isHTML(true);
          $mail->Body = "<div style=\"text-align: center;\">
                <img src=\"https://zupimages.net/up/22/51/ju54.png\" alt=\"logo\" style=\"border-radius: 50%; width: 100px\">
            </div>
            <p style=\"text-align: center;\">Bonjour <strong>$this->fullname</strong>,</p>
            <hr>
            <p style=\"text-align: center;\">
                Cliquez sur ce <a href=\"http://localhost:8081/verify?token=$this->token\" style=\"text-decoration:none\"><strong>lien</strong></a> pour valider votre email !
            </p>
            <br>
            <p style=\"overflow-wrap: break-word\">
                Si le lien ne fonctionne pas, veuillez recopier ce lien dans votre navigateur :
                <br>
                http://localhost:8081/verify?token=$this->token
            </p>";
            $mail->AltBody = "Cliquez sur ce <a href=\"http://localhost:8081/verify?token=$this->token\" style=\"text-decoration:none\"><strong>lien</strong></a> pour valider votre email !";

            $mail->send();
        } catch (Exception $e) {
          $this->error = "Une erreur s'est produite lors de l'envoi du message. Veuillez réessayer plus tard."; // . "$mail->ErrorInfo";
        }
    }

    // Process the form, insert the user into the database, send the verification email and redirect the user if validation passes
    public function processForm() 
    {
        if (!$this->error && isset($this->fullname, $this->email, $this->password)) {
            $this->database->createTableUsers();
            $query = $this->database->getPDO()->query("SELECT COUNT(*) as count FROM users");
            $count = $query->fetchColumn();
            $this->role = ($count == 0) ? 'admin' : 'user';
            $this->database->insertUser($this->fullname, $this->email, $this->password, $this->token, $this->role, $this->createdAt, $this->updatedAt);
            $this->handleRegistrationFormSubmission();
            FlashMessage::setFlashMessage("Vous devez d'abord valider votre adresse email !", 'info');
            header('Location: login');
            exit();
        }
    }

    // Return any error that occurred during form validation
    public function getError() {
        return $this->error;
    }
}