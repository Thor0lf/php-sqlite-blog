<article class="card h-100">
    <div class="card-header text-end">
        <small class="text-muted"><em><?= $db->getCategoryById($article->category_id)->name ?></em></small>
    </div>
    <img src="/img/<?= $detect->isMobile() ? 'mobile_resolution' : 'uploads' ?>/<?= $article->formFile ?>" class="card-img-body" alt="<?= pathinfo($article->formFile, PATHINFO_FILENAME) ?>">
    <div class="card-body">
        <h5 class="card-title fw-bold"><?= $article->title?></h5>
        <span class="card-text card-article-content" id="editor"><?= mb_substr($article->content, 0, mb_strpos($article->content, ' ', 160)) . '...' ?> <button type="button" class="btn btn-outline-secondary btn-sm border-0">En savoir plus</button></span>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center<?= $db->getCommentsCountByArticle($article->id) > 0 || $db->getLikesCountByArticle($article->id) >0 ? ' py-0' : '' ?>">
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
</article>

