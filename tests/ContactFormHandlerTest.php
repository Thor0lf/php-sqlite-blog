<?php

require 'vendor/autoload.php';
use PHPUnit\Framework\TestCase;
use App\ContactFormHandler;

class ContactFormHandlerTest extends TestCase
{
    public function testHandleFormSubmissionWithValidData()
    {
        // Préparation des données de test
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'John Doe';
        $_POST['email'] = 'john.doe@example.com';
        $_POST['subject'] = 'Sujet du message';
        $_POST['content'] = 'Contenu du message';

        // Instanciation de la classe ContactFormHandler
        $contactFormHandler = new ContactFormHandler();

        // Exécution de la méthode handleFormSubmission()
        $contactFormHandler->handleFormSubmission();

        // Vérification des résultats
        $this->assertFalse($contactFormHandler->getError());
        $this->assertEquals("Votre message a été envoyé avec succès.", $contactFormHandler->getSuccess());
    }

    public function testHandleFormSubmissionWithMissingData()
    {
        // Préparation des données de test
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = '';
        $_POST['email'] = '';
        $_POST['subject'] = '';
        $_POST['content'] = '';

        // Instanciation de la classe ContactFormHandler
        $contactFormHandler = new ContactFormHandler();

        // Exécution de la méthode handleFormSubmission()
        $contactFormHandler->handleFormSubmission();

        // Vérification des résultats
        $this->assertEmpty($_POST['name']);
        $this->assertEmpty($_POST['email']);
        $this->assertEmpty($_POST['subject']);
        $this->assertEmpty($_POST['content']);
        $this->assertFalse($contactFormHandler->getSuccess());
        $this->assertEquals("Un des champs est vide.", $contactFormHandler->getError());
    }
}