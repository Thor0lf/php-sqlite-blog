<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

class ContactFormHandler {

  private $error;
  private $success;
  private $name;
  private $email;
  private $subject;
  private $content;

  public function __construct() 
  {
    $this->error = false;
    $this->success = false;
    $this->name = $_POST['name'] ?? null;
    $this->email = $_POST['email'] ?? '';
    $this->subject = $_POST['subject'] ?? null;
    $this->content = $_POST['content'] ?? null;
  }

  //Handle the form submission, validate the form inputs, send an email if validation passes, and set success or error messages
  public function handleFormSubmission() 
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      //Check the empty inputs
      if (empty($this->name) || empty($this->email) || empty($this->subject) || empty($this->content)) {
        $this->error = "Un des champs est vide.";
      } else {
        //Send of the email
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

          // Email parameters
          $mail->setFrom($this->email, 'Lorem Ipsum');
          $mail->addAddress($_ENV['EMAIL_TO']);
          $mail->Subject = "$this->subject de $this->name";
          $mail->Body = $this->content;

          $mail->send();

          $this->success = "Votre message a été envoyé avec succès.";
          // Resetting the form fields
          $this->name = $this->email = $this->subject = $this->content = '';
        } catch (Exception $e) {
          $this->error = "Une erreur s'est produite lors de l'envoi du message. Veuillez réessayer plus tard."; // . "$mail->ErrorInfo";
        }
      }
    }
  }

  // Return any error that occurred during form validation or email sending
  public function getError() 
  {
    return $this->error;
  }

  // Return a success message if the email was sent successfully
  public function getSuccess() 
  {
    return $this->success;
  }
}
?>