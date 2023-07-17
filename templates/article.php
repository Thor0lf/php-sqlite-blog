<?php 

use App\AddEditComment;
use App\AddRemoveLikes;
use App\CommentsListing;
use App\Database;
use App\Functions;

require '../vendor/autoload.php';

$auth = Database::getAuth();
$user = $auth->user();
$userId = $user->id ?? null;

$addEditComment = new AddEditComment();
$addEditComment->validateForm();
$addEditComment->processForm();
$referer = $_SERVER['REQUEST_URI'];

$articleId = $_GET['id'] ?? null;

$db = new Database();
$article = $db->getArticleById($articleId);
$category = $db->getCategoryById($article->category_id);
$pageTitle = "$category->name - $article->title";
$keywords = $article->metadata;

$currentPage = htmlentities($_GET['page'] ?? 1) ;
$articlesPerPage = 9;

$functions = new Functions();

// Get all the comments of the article with pagination
$commentsListing = new CommentsListing($currentPage, $articlesPerPage);
$comments = $commentsListing->getAllCommentsByArticle($article->id);
$totalComments = $commentsListing->getTotalComments();
$totalPages = $commentsListing->getTotalPages();

$likes = new AddRemoveLikes();
$likes->ProcessForm();
$isLiked = $likes->getLikeByUser();

$numberOfLikes = $likes->getTotalLikes();

if (isset($_GET['del'])) {
    $deleteId = $_GET['del'];
    $addEditComment->deleteComment($deleteId);
}

?>

<?php if ($likes->getError()): ?>
    <div class="alert alert-danger">
        <?= $likes->getError() ?>
    </div>
<?php endif ?>

<?php if ($commentsListing->getError()): ?>
    <div class="alert alert-danger">
        <?= $commentsListing->getError() ?>
    </div>
<?php endif ?>

<?php if ($addEditComment->getError()): ?>
    <div class="alert alert-danger">
        <?= $addEditComment->getError() ?>
    </div>
<?php endif ?>

<section class="container my-5">
    <article class="card">
        <div class="px-lg-5">
            <img src="/img/uploads/<?= $article->formFile ?>" class="card-img-top px-lg-5" alt="<?= $article->formFile ?>" width="100%" height="100%" />
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <h5><em><?= $category->name ?></em></h5>
                <div class="d-flex align-items-center">
                    <form method="post" action="">
                        <button type="submit" name="like" class="btn btn-none btn-sm border-0 <?= $isLiked ? 'liked' : ''; ?>"<?php if ($user): ?><?= ' id="likeButton"' ?> title="Liker l'article"<?php else: ?><?= ' disabled' ?><?php endif ?>>
                            <?php if ($isLiked): ?><i class="fa-solid fa-heart fa-xl"></i><?php else : ?><i class="fa-regular fa-heart fa-xl"></i><?php endif ?>
                        </button>
                    </form>
                    <span class="align-items-center"><?= $numberOfLikes ?></span>
                </div>
            </div>
            <h2 class="fw-bold"><?= $article->title ?></h2>
            <p class="fst-italic">Par <?= $db->getUserById($article->user_id)->fullname ?>, le <?= $functions->getFormattedDate($article->created_at) ?></p>
            <label for="textarea-content">
            <textarea id="content-article" class="form-control" id="textarea-content" style="height: 80vh" disabled><?= $article->content ?></textarea>
        </div>
    </article>

    <hr class="my-4" id="tag">
    <?php if (!$comments || $totalComments === null): ?>
        <p class="text-center fw-bold">Soyez le premier à laisser un commentaire.</p>
    <?php else: ?>
    <h4 class="text-center my-4">Liste des commentaires</h4>
    <?php foreach ($comments as $comment): ?>
    <section class="col-md-12 mx-auto my-3">
        <div class="d-flex align-items-center highlight-toolbar bg-secondary bg-opacity-25 ps-3 pe-2 py-1 border border-secondary border-bottom-0 rounded-top">
            <small class="font-monospace text-muted"><?= $db->getUserById($comment->user_id)->fullname ?>, le <?= $functions->getFormattedDate($comment->created_at) ?><?php if ($functions->getFormattedDate($comment->created_at) !== $functions->getFormattedDate($comment->updated_at)): ?><?= ' et modifié le ' . $functions->getFormattedDate($comment->updated_at) ?><?php endif ?></small>

            <?php if ($userId === $comment->user_id): ?>
            <div class="d-flex ms-auto">
                <span data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom<?= $comment->id ?>" aria-controls="offcanvasBottom">
                    <button type="button" class="btn mt-1 fa fa-edit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Éditer son commentaire"></button>
                </span>
                <!-- Button trigger modal -->
                <span data-bs-toggle="modal" data-bs-target="#modalComment<?= $comment->id ?>">
                    <button type="button" class="btn text-nowrap fa-solid fa-trash mt-1" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Supprimer son commentaire">
                    </button>
                </span>

                <!-- Modal -->
                <div class="modal fade" id="modalComment<?= $comment->id ?>" tabindex="-1" aria-labelledby="modalLabelComment" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h2 class="modal-title fs-5" id="modalLabelComment">
                                    <?= $db->getUserById($comment->user_id)->fullname ?>, le <?= $functions->getFormattedDate($comment->updated_at) ?>
                                    <?php if ($comment->created_at !== $comment->updated_at): ?>
                                        et modifié le <?= $comment->updated_at ?>
                                    <?php endif ?>
                                </h2>

                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Voulez-vous vraiment supprimer ce commentaire ?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                <a href="/article?del=<?= $comment->id ?>" type="button" class="btn btn-danger">Supprimer</a>
                            </div>
                        </div>
                    </div>
                </div>

                <form action="" method="POST" enctype="application/x-www-form-urlencoded">
                    <div class="offcanvas offcanvas-bottom bg-light bg-opacity-75" data-bs-scroll="true" tabindex="-1" id="offcanvasBottom<?= $comment->id ?>" aria-labelledby="offcanvasBottomLabel">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="offcanvasBottomLabel">Modification du commentaire</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                        </div>
                        <div class="form-group offcanvas-body small text-center">
                            <label class="d-grid">
                                <input type="hidden" name="comment_id" value="<?= $comment->id ?>">
                                <textarea class="col-12 border border-secondary p-2 comment-textarea" name="comment"><?= $comment->comment ?></textarea>
                            </label>
                            <button class="btn btn-primary btn-block mt-4" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Modifier le commentaire">
                                Modifier
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <?php endif ?>

        </div>
        <section class="d-grid">
            <p class="col-12 border border-secondary border-top-0 p-2 comment-textarea" oninput="adjustTextareaHeight(this)" readonly><?= $comment->comment ?></p>
        </section>
    </section>
    <?php endforeach ?>
    <?php endif ?>

<!-- Pagination -->
<?php if ($comments): ?>
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
<?php endif ?>

    <hr class="mt-5 mb-3">
    <?php if ($user): ?>
    <form class="col-md-12 mx-auto" action="" method="POST" enctype="application/x-www-form-urlencoded">
        <h4 class="text-center">Laisser un commentaire</h4>
        <input type="text" name="referer" value="<?= $referer ?>" hidden>
        <div class="form-group mb-2 d-grid">
            <label for="new-comment-textarea"><textarea class="col-12 p-3" id="new-comment-textarea" name="comment"></textarea>
        </div>
        <div class="form-group text-center">
            <button class="btn btn-primary btn-block" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Envoyer le commentaire">
                Envoyer
            </button>
        </div>
    </form>
    <?php else: ?>
    <section class="col-md-12 mx-auto my-3 text-center">
        <h6 class="text-danger">Pour laisser un commentaire, vous <b>devez être connecté !</b></h6>
    </section>
    <?php endif ?>
</section>