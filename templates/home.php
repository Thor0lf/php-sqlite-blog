<?php
use App\Admin\ArticlesListing;
use App\Database\Database;
use App\Helpers\Helpers;
use App\Users\AddRemoveLikes;

require_once '../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Page d'accueil";

$currentPage = (int)($_GET['page'] ?? 1) ;
$articlesPerPage = 9;

$articlesListing = new ArticlesListing($currentPage, $articlesPerPage);
$articles = $articlesListing->getAllArticles();

// To get the articles for the carousel
if ($articles !== null) {
  $articlesCarousel = array_slice($articles, 0, 3);
}
// To get the articles for the cards
if (($currentPage) < 2 && $articles !== null) {
  $articles = array_slice($articles, 3);
}
// To get the pagination
$articles = $articlesListing->getAllArticles();
$totalArticles = $articlesListing->getTotalArticles();
$totalPages = $articlesListing->getTotalPages();

$db = new Database();

// To get the number of likes by article
$likes = new AddRemoveLikes();
$numberOfLikes = $likes->getTotalLikes();

$functions = new Helpers();


?>

<?php if (!$articles): ?>
    <?php require 'parts/home/noArticle.php' ?>
<?php endif ?>

<!-- Carousel -->
<?php if (($currentPage) < 2): ?>
  <?php if (isset($articlesCarousel)): ?>
    <?php require 'parts/home/articlesCarousel.php' ?>
  <?php endif ?>
<?php endif ?>

<!-- Articles cards -->
<?php if (isset($articles)): ?>

<section class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
  <?php foreach ($articles as $article) : ?>
    <article class="col">
      <a href="/article?id=<?= $article->id ?>">
        <?php require 'parts/home/articlesCard.php' ?>
      </a>
    </article>
  <?php endforeach; ?>
</section>
<?php endif ?>

<!-- Pagination -->
<?php require 'parts/pagination.php' ?>