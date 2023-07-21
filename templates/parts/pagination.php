<div class="d-flex justify-content-center mb-3">
  <?php if ($currentPage > 1): ?>
    <a href="?<?= isset($_GET['category']) ? "category={$article->category_id}&" : '' ?><?= isset($_GET['id']) ? "id={$article->id}&" : '' ?>page=1<?= isset($_GET['id']) ? "#tag" : '' ?>" class="btn btn-outline-primary me-2">&laquo; Première</a>
    <a href="?<?= isset($_GET['category']) ? "category={$article->category_id}&" : '' ?><?= isset($_GET['id']) ? "id={$article->id}&" : '' ?>page=<?= $currentPage - 1 ?><?= isset($_GET['id']) ? "#tag" : '' ?>" class="btn btn-outline-primary me-2">&lsaquo; Précédente</a>
  <?php endif ?>

  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <?php if ($i == $currentPage): ?>
        <a href="?<?= isset($_GET['category']) ? "category={$article->category_id}&" : '' ?><?= isset($_GET['id']) ? "id={$article->id}&" : '' ?>page=<?= $i ?><?= isset($_GET['id']) ? "#tag" : '' ?>" class="btn btn-outline-danger disabled me-2"><?= $i ?></a>
    <?php else: ?>
      <a href="?<?= isset($_GET['category']) ? "category={$article->category_id}&" : '' ?><?= isset($_GET['id']) ? "id={$article->id}&" : '' ?>page=<?= $i ?><?= isset($_GET['id']) ? "#tag" : '' ?>" class="btn btn-outline-primary me-2"><?= $i ?></a>
    <?php endif ?>
  <?php endfor ?>

  <?php if ($currentPage < $totalPages): ?>
    <a href="?<?= isset($_GET['category']) ? "category={$article->category_id}&" : '' ?><?= isset($_GET['id']) ? "id={$article->id}&" : '' ?>page=<?= $currentPage + 1 ?><?= isset($_GET['id']) ? "#tag" : '' ?>" class="btn btn-outline-primary me-2">Suivante &rsaquo;</a>
    <a href="?<?= isset($_GET['category']) ? "category={$article->category_id}&" : '' ?><?= isset($_GET['id']) ? "id={$article->id}&" : '' ?>page=<?= $totalPages ?><?= isset($_GET['id']) ? "#tag" : '' ?>" class="btn btn-outline-primary">Dernière &raquo;</a>
  <?php endif ?>
</div>