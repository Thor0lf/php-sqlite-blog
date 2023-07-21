<?php

use PHPUnit\Framework\TestCase;
use App\Admin\AddEditCategory;
use App\Database\Database;
use App\Models\Category;
class AddEditCategoryTest extends TestCase
{
    private $addEditCategory;
    private $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
    }

    public function testValidFormWithEmptyName()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = '';

        // Instantiate AddEditCategory class
        $addEditCategory = new AddEditCategory();

        // Execute validateForm method
        $addEditCategory->validateForm();

        // Verify the error message
        $this->assertEmpty($_POST['name']);
        $this->assertEquals("La catégorie doit avoir un nom.", $addEditCategory->getError());
    }

    public function testValidateFormWithShortName()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'AB';

        // Instantiate AddEditCategory class
        $addEditCategory = new AddEditCategory();

        // Execute validateForm method
        $addEditCategory->validateForm();

        // Verify the error message
        $this->assertEquals("Le nom de la catégorie est trop court.", $addEditCategory->getError());
    }

    public function testValidateFormWithLongName()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = str_repeat('A', 251);

        // Instantiate AddEditCategory class
        $addEditCategory = new AddEditCategory();

        // Execute validateForm method
        $addEditCategory->validateForm();

        // Verify the error message
        $this->assertEquals("Le nom de la catégorie est trop long.", $addEditCategory->getError());
    }

    public function testProcessFormCreateCategory()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST['name'] = 'Test Category';

        // Instantiate AddEditCategory class
        $addEditCategory = new AddEditCategory();

        // Mock the insertCategory method
        $this->database->method('getCategoryById')->willReturn(null);
        $this->database->method('insertCategory')->willReturn(true);

        // Execute processForm method
        $addEditCategory->validateForm();

        // Verify that there is no error
        $this->assertEmpty($addEditCategory->getError());
    }

    public function testProcessFormUpdateCategory()
    {
        // Prepare test data
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['id'] = 4; // Assuming the category ID is 1
        $_POST['name'] = 'Updated Category';

        // Instantiate AddEditCategory class
        $addEditCategory = new AddEditCategory();

        // Mock the modifyCategory method
        $this->database->method('modifyCategory')->willReturn(true);

        // Execute processForm method
        $addEditCategory->validateForm();

        // Verify that there is no error
        $this->assertEmpty($addEditCategory->getError());
    }

    protected function tearDown(): void
    {
        $this->addEditCategory = null;
    }
}