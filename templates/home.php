<?php

use App\AddRemoveLikes;
use App\ArticlesListing;
use App\Database;
use App\Functions;

require_once '../vendor/autoload.php';

// A supprimer quand je supprimerai les DUMP
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Page d'accueil";

$currentPage = htmlentities($_GET['page'] ?? 1) ;
$articlesPerPage = 9;

// Obtenez les articles du carrousel
$articlesListing = new ArticlesListing($currentPage, $articlesPerPage);
$articles = $articlesListing->getAllArticles();
if ($articles !== null) {
  $articlesCarousel = array_slice($articles, 0, 3);
}

// Obtenez tous les articles avec pagination
$articles = $articlesListing->getAllArticles();
$totalArticles = $articlesListing->getTotalArticles();
$totalPages = $articlesListing->getTotalPages();

$db = new Database();

$likes = new AddRemoveLikes();
$numberOfLikes = $likes->getTotalLikes();

$functions = new Functions();

if ((($_GET['page']) ?? '') < 2 && $articles !== null) {
  $articles = array_slice($articles, 3);
}

?>

<?php if ((($_GET['page']) ?? '') < 2): ?>
  <!-- Carousel -->
  <?php if (isset($articlesCarousel)): ?>
  <div id="carouselCaptions" class="carousel slide mb-5" data-bs-ride="carousel">

    <div class="carousel-indicators">
        <?php foreach ($articlesCarousel as $key => $article) : ?>
          <button type="button" data-bs-target="#carouselCaptions" data-bs-slide-to="<?= $key ?>" class="<?= ($key === 0) ? 'active' : '' ?>" aria-current="<?= ($key === 0) ? 'true' : 'false' ?>" aria-label="Slide <?= $key + 1 ?>"></button>
        <?php endforeach; ?>
    </div>
    <div class="carousel-inner">
    <?php foreach ($articlesCarousel as $key => $article) : ?>
      <a href="/article?id=<?= $article->id ?>">
        <div class="carousel-item <?= ($key === 0) ? 'active' : '' ?>" data-bs-interval="4000">
          <img src="/img/uploads/<?= $article->formFile ?>" class="d-block w-100" alt="<?= $article->formFile ?>" width="1280">
          <span class="position-absolute bottom-0 end-0 mb-3 me-3">
            <?php if ($db->getCommentsCountByArticle($article->id) > 0): ?>
              <button class="btn btn-secondary p-0">
                <span class="badge text-bg-secondary"><i class="fa-regular fa-message me-1"></i> <?= $db->getCommentsCountByArticle($article->id) ?></span>
              </button>
            <?php endif ?>
            <?php if ($db->getLikesCountByArticle($article->id)): ?>
              <button class="btn btn-secondary p-0">
                <span class="badge text-bg-secondary"><i class="fa-regular fa-heart heart-home me-1"></i> <?= $db->getLikesCountByArticle($article->id) ?></span>
              </button>
            <?php endif ?>
          </span>
          <div class="carousel-caption d-none d-md-block">
          <h5 class="fw-bold"><?= $article->title ?></h5>
            <p><?= substr($article->content, 0, 100) . '...' ?></p>
          </div>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Précédent</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Suivant</span>
    </button>
  </div>
  <?php else: ?>
    <div class="d-flex justify-content-center">
      <div class="card text-bg-warning text-center col-8 mb-3">
        <div class="card-header">Information</div>
        <div class="card-body">
          <h5 class="card-title">Aucun article présent</h5>
          <p class="card-text">Faites briller votre site web en écrivant des articles savoureux ! Remplissez-le de contenu alléchant pour captiver vos visiteurs et les faire revenir en redemandant. Alors, sortez votre plume créative et régalez-nous avec vos articles délicieux !</p>
        </div>
      </div>
    </div>
  <?php endif ?>
<?php endif ?>

  <!-- Articles list -->
  <?php if (isset($articles)): ?>

<section class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
  <?php foreach ($articles as $article) : ?>
    <article class="col">
      <a href="/article?id=<?= $article->id ?>">
        <div class="card h-100">
          <div class="card-header text-end">
            <small class="text-muted"><em><?= $db->getCategoryById($article->category_id)->name ?></em></small>
          </div>
          <img src="/img/uploads/<?= $article->formFile ?>" class="card-img-body" alt="<?= $article->formFile ?>" width="100%" height="100%">
          <div class="card-body">
            <h5 class="card-title fw-bold"><?= $article->title?></h5>
            <span class="card-text card-article-content" id="editor"><?= substr($article->content, 0, 130) . '...' ?> <button type="button" class="btn btn-outline-secondary btn-sm border-0">En savoir plus</button></span>
          </div>
          <div class="card-footer d-flex justify-content-between align-items-center<?php if ($db->getCommentsCountByArticle($article->id) > 0): ?><?= ' py-0' ?><?php endif ?>">
            <small class="text-muted">Le <?= $functions->getFormattedDate($article->created_at) ?></small>
            <span>
              <?php if ($db->getCommentsCountByArticle($article->id) > 0): ?>
                <button type="button" class="btn btn-none px-0">
                 <span class="badge text-bg-secondary"><i class="fa-regular fa-message me-1"></i> <?= $db->getCommentsCountByArticle($article->id) ?></span>
                </button>
              <?php endif ?>
              <?php if ($db->getLikesCountByArticle($article->id) > 0): ?>
                <button type="button" class="btn btn-none px-0">
                 <span class="badge text-bg-secondary"><i class="fa-regular fa-heart heart-home me-1"></i> <?= $db->getLikesCountByArticle($article->id) ?></span>
                </button>
              <?php endif ?>
            </span>
          </div>
        </div>
      </a>
    </article>
  <?php endforeach; ?>
</section>
<?php endif ?>

<!-- Pagination -->
<div class="d-flex justify-content-center mb-3">
  <?php if ($currentPage > 1): ?>
    <a href="?page=1" class="btn btn-outline-primary me-2">&laquo; Première</a>
    <a href="?page=<?= $currentPage - 1 ?>" class="btn btn-outline-primary me-2">&lsaquo; Précédente</a>
  <?php endif ?>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i == $currentPage): ?>
        <a href="?page=<?= $i ?>" class="btn btn-outline-danger disabled me-2"><?= $i ?></a>
    <?php else: ?>
      <a href="?page=<?= $i ?>" class="btn btn-outline-primary me-2"><?= $i ?></a>
    <?php endif ?>
  <?php endfor ?>

  <?php if ($currentPage < $totalPages): ?>
    <a href="?page=<?= $currentPage + 1 ?>" class="btn btn-outline-primary me-2">Suivante &rsaquo;</a>
    <a href="?page=<?= $totalPages ?>" class="btn btn-outline-primary">Dernière &raquo;</a>
  <?php endif ?>
</div>


<?php dump($_COOKIE) ?>
<?php dump($_SESSION) ?>