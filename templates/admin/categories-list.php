<?php
use App\Admin\CategoriesListing;
use App\Database\Database;

require '../vendor/autoload.php';

Database::getAuth()->requireRole('admin');

$pageTitle = "Liste des catégories";

$currentPage = htmlentities($_GET['page'] ?? 1) ;
$categoriesPerPage = 10;

$categoriesListing = new CategoriesListing($currentPage, $categoriesPerPage);
$categories = $categoriesListing->getAllCategories();
$totalCategories = $categoriesListing->getTotalCategories();
$totalPages = $categoriesListing->getTotalPages();
$articlesCount = new Database();

?>

<?php if ($categoriesListing->getError()): ?>
    <div class="alert alert-danger">
        <?= $categoriesListing->getError() ?>
    </div>
<?php endif ?>

<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h2>Liste des catégories
      <a
        class="btn btn-info float-right btn-sm"
        href="/add-edit-category"
      ><i class="fa fa-plus"></i> Créer une catégorie</a>
    </h2>
    <h6 class="mt-4">Nombre total de catégories : <?= $totalCategories ?></h6>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Titre <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Nombre d'articles</th>
            <th>Date de modification <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php if ($categoriesListing->getTotalCategories() < 1): ?>
          <?php else: ?>
          <?php foreach ($categories as $category): ?> 
            <tr>
              <td><?= $category->id ?></td>
              <td><?= $category->name ?></td>
              <td><?= $articlesCount->getArticlesCountByCategory($category->id) ?></td>
              <td><?= $category->updated_at ?></td>
              <td class="text-center">
                <a href="add-edit-category?id=<?= $category->id ?>" class="fa fa-edit me-2" aria-hidden="true"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Éditer l'article"
                  ></a>
                <!-- Button trigger modal -->
                <span data-bs-toggle="modal" data-bs-target="#modalArticle<?= $category->id ?>">
                  <button type="button" class="btn mb-1 fa-solid fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Supprimer l'article">
                </button></span>

                <!-- Modal -->
                <div class="modal fade" id="modalArticle<?= $category->id ?>" tabindex="-1" aria-labelledby="modalLabelArticle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalLabelArticle"><?= $category->name ?></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Voulez-vous vraiment supprimer cet article ?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="add-edit-category?del=<?= $category->id ?>" type="button" class="btn btn-danger">Supprimer</a>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach ?>
          <?php endif ?>
        </tbody>
      </table>
    </div>
  </div>

<!-- Pagination -->
<?php require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'parts/pagination.php' ?>