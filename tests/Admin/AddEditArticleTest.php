<?php

use PHPUnit\Framework\TestCase;
use App\Admin\AddEditArticle;
use App\Database\Database;

class AddEditArticleTest extends TestCase
{
    private $addEditArticle;
    private $database;
    private $userId;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
    }

    
    public function testValidFormWithMissingData()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = '';
        $_POST['content'] = '';
        $_FILES = [
            'formFile' => [
                'name'     => '',
                'tmp_name' => '',
                'error'    => 0,
            ],
        ];
        $_POST['metadata'] = '';
        $_POST['category_id'] = '';
        $this->userId = null;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Exécution de la méthode handleFormSubmission()
        $addEditArticle->validateForm();

        // Vérification des résultats
        $this->assertEmpty($_POST['title']);
        $this->assertEmpty($_POST['content']);
        $this->assertEmpty($_POST['metadata']);
        $this->assertEmpty($_FILES['formFile']['name']);
        $this->assertNull($this->userId);
        $this->assertEmpty($_POST['category_id']);
        $this->assertEquals("L'un des champs est vide." || "L'image est manquante." || "L'utilisteur n'a pas pu être trouvé." || "La catégorie est manquante.", $addEditArticle->getError());
    }

    public function testValidFormWithEmptyTitle()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = '';
        $_POST['content'] = 'Test Content';
        $_FILES = [
            'formFile' => [
                'name' => 'test.png',
                'tmp_name' => '/tmp/phpyT1fbr',
                'error' => 0,
            ],
        ];
        $_POST['metadata'] = 'Test Metadata';
        $_POST['category_id'] = (int)('5');
        $this->userId = 1;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertEmpty($_POST['title']);
        $this->assertNotEmpty($_POST['content']);
        $this->assertNotEmpty($_POST['metadata']);
        $this->assertNotEmpty($_FILES['formFile']['name']);
        $this->assertIsInt($this->userId);
        $this->assertIsInt($_POST['category_id']);
        $this->assertEquals("L'un des champs est vide.", $addEditArticle->getError());
    }

    public function testValidFormWithEmptyContent()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = 'Test Title';
        $_POST['content'] = '';
        $_FILES = [
            'formFile' => [
                'name' => 'test.png',
                'tmp_name' => '/tmp/phpyT1fbr',
                'error' => 0,
            ],
        ];
        $_POST['metadata'] = 'Test Metadata';
        $_POST['category_id'] = (int)('5');
        $this->userId = 1;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertNotEmpty($_POST['title']);
        $this->assertEmpty($_POST['content']);
        $this->assertNotEmpty($_POST['metadata']);
        $this->assertNotEmpty($_FILES['formFile']['name']);
        $this->assertIsInt($this->userId);
        $this->assertIsInt($_POST['category_id']);
        $this->assertEquals("L'un des champs est vide.", $addEditArticle->getError());
    }

    public function testValidFormWithEmptyFile()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = 'Test Title';
        $_POST['content'] = 'Test Content';
        $_FILES = [
            'formFile' => [
                'name' => '',
                'tmp_name' => '',
            ],
        ];
        $_POST['metadata'] = 'Test Metadata';
        $_POST['category_id'] = (int)('5');
        $this->userId = 1;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertNotEmpty($_POST['title']);
        $this->assertNotEmpty($_POST['content']);
        $this->assertNotEmpty($_POST['metadata']);
        $this->assertEmpty($_FILES['formFile']['name']);
        $this->assertIsInt($this->userId);
        $this->assertIsInt($_POST['category_id']);
        $this->assertEquals("L'image est manquante.", $addEditArticle->getError());
    }

    public function testValidFormWithEmptyMetadata()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = 'Test Title';
        $_POST['content'] = 'Test Content';
        $_FILES = [
            'formFile' => [
                'name' => 'test.png',
                'tmp_name' => '/tmp/phpyT1fbr',
                'error' => 0,
            ],
        ];
        $_POST['metadata'] = '';
        $_POST['category_id'] = (int)('5');
        $this->userId = 1;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertNotEmpty($_POST['title']);
        $this->assertNotEmpty($_POST['content']);
        $this->assertEmpty($_POST['metadata']);
        $this->assertNotEmpty($_FILES['formFile']['name']);
        $this->assertIsInt($this->userId);
        $this->assertIsInt($_POST['category_id']);
        $this->assertEquals("L'un des champs est vide.", $addEditArticle->getError());
    }

    public function testValidFormWithEmptyCategory()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = 'Test Title';
        $_POST['content'] = 'Test Content';
        $_FILES = [
            'formFile' => [
                'name' => 'test.png',
                'tmp_name' => '/tmp/phpyT1fbr',
                'error' => 0,
            ],
        ];
        $_POST['metadata'] = 'Test Metadata';
        $_POST['category_id'] = '';
        $this->userId = 1;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertNotEmpty($_POST['title']);
        $this->assertNotEmpty($_POST['content']);
        $this->assertNotEmpty($_POST['metadata']);
        $this->assertNotEmpty($_FILES['formFile']['name']);
        $this->assertIsInt($this->userId);
        $this->assertEmpty($_POST['category_id']);
        $this->assertEquals("La catégorie est manquante.", $addEditArticle->getError());
    }

    public function testValidFormWithNullUser()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = 'Test Title';
        $_POST['content'] = 'Test Content';
        $_FILES = [
            'formFile' => [
                'name' => 'test.png',
                'tmp_name' => '/tmp/phpyT1fbr',
                'error' => 0,
            ],
        ];
        $_POST['metadata'] = 'Test Metadata';
        $_POST['category_id'] = (int)('5');
        $this->userId = null;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertNotEmpty($_POST['title']);
        $this->assertNotEmpty($_POST['content']);
        $this->assertNotEmpty($_POST['metadata']);
        $this->assertNotEmpty($_FILES['formFile']['name']);
        $this->assertNull($this->userId);
        $this->assertNotEmpty($_POST['category_id']);
        $this->assertEquals("L'utilisteur n'a pas pu être trouvé.", $addEditArticle->getError());
    }

    public function testProcessForm()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['title'] = 'Test Title';
        $_POST['content'] = 'Test Content';
        $_FILES = [
            'formFile' => [
                'name' => 'test.png',
                'tmp_name' => '/tmp/phpyT1fbr',
            ],
        ];
        $_POST['metadata'] = 'Test Metadata';
        $_POST['category_id'] = 5;
        $this->userId = 1;

        // Instantiate AddEditArticle class
        $addEditArticle = new AddEditArticle();

        $this->database->method('getArticleById')->willReturn(null);
        $this->database->method('insertArticle')->willReturn(true);

        // Execute validateForm() method
        $addEditArticle->validateForm();

        // Verify the results
        $this->assertNotEmpty($_POST['title']);
        $this->assertNotEmpty($_POST['content']);
        $this->assertNotEmpty($_POST['metadata']);
        $this->assertNotEmpty($_FILES['formFile']['name']);
        $this->assertIsInt($this->userId);
        $this->assertNotEmpty($_POST['category_id']);
        $this->assertNotTrue($addEditArticle->getError());
    }

    protected function tearDown(): void
    {
        $this->addEditArticle = null;
    }
}