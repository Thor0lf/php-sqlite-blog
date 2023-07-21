<?php
use App\Admin\ArticlesListing;
use App\Database\Database;

require '../vendor/autoload.php';

Database::getAuth()->requireRole('admin');

$pageTitle = "Liste des articles";

$currentPage = htmlentities($_GET['page'] ?? 1) ;
$articlesPerPage = 6;

$articlesListing = new ArticlesListing($currentPage, $articlesPerPage);
$articles = $articlesListing->getAllArticles();
$totalArticles = $articlesListing->getTotalArticles();
$totalPages = $articlesListing->getTotalPages();

$db = new Database();

?>

<div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h2>Liste des articles
      <a
        class="btn btn-info float-right btn-sm"
        href="/add-edit-article"
      ><i class="fa fa-plus"></i> Créer un article</a>
    </h2>
    <h6 class="mt-4">Nombre total d'articles : <?= $totalArticles ?></h6>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Titre <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Auteur <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Date de modification <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Commentaires</th>
            <th>Likes</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($articles as $article): ?> 
            <tr>
              <td><?= $article->id ?></td>
              <td><?= $article->title ?></td>
              <td><?= $db->getUserById($article->user_id)->fullname ?></td>
              <td><?= $article->updated_at ?></td>
              <td class="text-center"><?= $db->getCommentsCountByArticle($article->id) ?></td>
              <td class="text-center"><?= $db->getLikesCountByArticle($article->id) ?></td>
              <td class="text-center">
                <a href="add-edit-article?id=<?= $article->id ?>" class="fa fa-edit me-2" aria-hidden="true"
                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Éditer l'article"
                  ></a>
                <!-- Button trigger modal -->
                <span data-bs-toggle="modal" data-bs-target="#modalArticle<?= $article->id ?>">
                  <button type="button" class="btn mb-1 fa-solid fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Supprimer l'article">
                </button></span>

                <!-- Modal -->
                <div class="modal fade" id="modalArticle<?= $article->id ?>" tabindex="-1" aria-labelledby="modalLabelArticle" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalLabelArticle"><?= $article->title ?></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Voulez-vous vraiment supprimer cet article ?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="add-edit-article?del=<?= $article->id ?>" type="button" class="btn btn-danger">Supprimer</a>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>

<!-- Pagination -->
<?php require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'parts/pagination.php' ?>
