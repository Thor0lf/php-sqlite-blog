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