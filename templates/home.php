<?php
use App\Admin\ArticlesListing;
use App\Admin\CategoriesListing;
use App\Database\Database;
use App\Helpers\Helpers;
use App\Users\AddRemoveLikes;
use Detection\MobileDetect;

require_once '../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Page d'accueil";

$currentPage = (int)($_GET['page'] ?? 1) ;
$articlesPerPage = 9;

$category = $_GET['category'] ?? null;
$articlesListing = new ArticlesListing($currentPage, $articlesPerPage, $category);
$articles = $articlesListing->getAllArticles();

// To get the articles for the carousel
if ($articles !== null && ($currentPage) < 2 && !$category) {
  $articlesCarousel = array_slice($articles, 0, 3);
}
// To get the articles for the cards
if (($currentPage) < 2 && $articles !== null && !$category) {
  $articles = array_slice($articles, 3);
}
// To get articles when a category is selected
if ($articles !== null && $category) {
  $articles = array_slice($articles, 0);
}
// To get the pagination
$totalArticles = $articlesListing->getTotalArticles();
$totalPages = $articlesListing->getTotalPages();

$db = new Database();

$categoriesListing = new CategoriesListing();
$categories = $categoriesListing->getAllCategories();

// To get the number of likes by article
$likes = new AddRemoveLikes();
$numberOfLikes = $likes->getTotalLikes();

$functions = new Helpers();

// Create a MobileDetect instance
$detect = new MobileDetect;

?>

<?php if (!$articles): ?>
    <?php require 'parts/home/noArticle.php' ?>
<?php endif ?>

<!-- Carousel -->
<?php if (($currentPage) < 2  && !$category): ?>
  <?php if (isset($articlesCarousel)): ?>
    <?php require 'parts/home/articlesCarousel.php' ?>
  <?php endif ?>
<?php endif ?>

<section class="col-md-4 m-auto mb-4">
  <form action="" method="get">
    <select class="form-select form-select-sm" name="category" aria-label=".form-select-sm example" onchange="this.form.submit()">
      <option value="">Afficher toutes les catégories</option>
      <?php foreach ($categories as $category): ?>
        <option value="<?= $category->id ?>" <?= (isset($_GET['category']) && (int)($_GET['category']) === $category->id) ? 'selected' : ''; ?>><?= $category->name ?></option>
      <?php endforeach ?>  
    </select>
  </form>
</section>
    
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