<?php
use App\Admin\AddEditCategory;
use App\Database\Database;

require ('../vendor/autoload.php');

Database::getAuth()->requireRole('admin');

$addEditCategory = new AddEditCategory;
$addEditCategory->validateForm();
$addEditCategory->processForm();

if (isset($_GET['del'])) {
    $deleteId = $_GET['del'];
    $addEditCategory->deleteCategory($deleteId);
} elseif (isset($_GET['id'])) {
    $pageTitle = "Modification d'une catégorie";
    $category = $addEditCategory->getCategory(); 
    $name = $category->name;
    $titlePage = 'Modifier la catégorie';
} else {
    $pageTitle = "Création d'une catégorie";
    $titlePage = 'Nouvelle catégorie';
}

if (!isset($_GET['id'])) {
    if ($addEditCategory->getError()) {
    $name = $addEditCategory->getName();
    }
}
    
?>

<?php if ($addEditCategory->getError()): ?>
    <div class="alert alert-danger">
        <?= $addEditCategory->getError() ?>
    </div>
<?php endif ?>
<div class="col-md-8 mx-auto my-3">
        <div class="card">
            <div class="card-header text-center bg-secondary bg-opacity-50">
                <h3><?= $titlePage ?></h3>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group my-4">
                        <input type="text" name="name" class="form-control" minlength="3" maxlength="250" placeholder="Nom de la catégorie" value="<?= htmlentities($name ?? '') ?>" autofocus>
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-block" type="submit">
                            Enregistrer la catégorie
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>