<?php
use App\Admin\AddEditArticle;
use App\Database\Database;

require ('../vendor/autoload.php');

$user = Database::getAuth()->user();

Database::getAuth()->requireRole('admin');

$addEditArticle = new AddEditArticle();
$addEditArticle->validateForm();
$addEditArticle->processForm();

if (isset($_GET['del'])) {
    $deleteId = $_GET['del'];
    $addEditArticle->deleteArticle($deleteId);
} elseif (isset($_GET['id'])) {
    $pageTitle = "Modifification d'un article";
    $article = $addEditArticle->getArticle();
    $title = $article->title;
    $content = $article->content;
    $metadata = $article->metadata;
    $categoryId = $article->category_id;
    $titlePage = "Modifier l'article";
} else {
    $pageTitle = "Création d'un article";
    $titlePage = "Nouvel article";
}

if (!isset($_GET['id'])) {
    if ($addEditArticle->getError()) {
        $title = $addEditArticle->getTitle();
        $content = $addEditArticle->getContent();
        $metadata = $addEditArticle->getMetadata();
    }
}

$categories = $addEditArticle->getCategory();

?>

<?php if ($addEditArticle->getError()): ?>
    <div class="alert alert-danger">
        <?= $addEditArticle->getError() ?>
    </div>
<?php endif ?>

<div class="col-md-12 mx-auto my-3">
        <div class="card">
            <div class="card-header text-center bg-secondary bg-opacity-50">
                <h3><?= htmlentities($titlePage) ?></h3>
            </div>
            <div class="card-body">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-2">
                        <select class="form-control" name="category_id" required>
                            <option <?php if (!isset($_GET['id'])) echo 'selected' ?>>Catégorie</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= htmlentities($category['id']) ?>" <?php if (isset($_GET['id']) && $article->category_id === $_GET['id']) echo 'selected' ?>><?= htmlentities($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <input type="text" name="title" class="form-control" minlength="3" maxlength="250" placeholder="Titre de l'article" value="<?= htmlentities($title ?? '') ?>" autofocus>
                    </div>
                    <div class="form-group mb-2">
                        <textarea id="content" name="content" class="form-control" minlength="3" placeholder="Contenu de l'article"><?= htmlentities($content ?? '') ?></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <input type="text" name="metadata" class="form-control" minlength="3" maxlength="250" placeholder="Mots-clés de l'article séparés par une virgule (ex: key1, key2, ...)" value="<?= htmlentities($metadata ?? '') ?>">
                    </div>
                    <?php if (isset($_GET['id'])) : ?>
                        <div class="form-group mb-3 w-50">
                            <input type="text" class="form-control" value="<?= htmlentities($article->formFile ?? '') ?>" readonly>
                        </div>
                    <?php endif ?>
                    <div class="mt-3 w-50 custom-file">
                        <input
                            type="file"
                            name="formFile"
                            id="formFile"
                            class="form-control custom-file-input"
                            accept=".jpg, .jpeg, .png, .webp"
                        />
                        <label for="formFile" class="custom-file-label mb-2">5 Mb maximum</label>
                    </div>
                    <div class="preview-images w-25 mb-3">
                    </div>
                    <div class="form-group text-center">
                        <button class="btn btn-primary btn-block" type="submit">
                            Enregistrer l'article
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Display a thumbnail of the image 
      $(document).ready(function() {
        let imagesPreview = function(input, placeToInsertImagePreview) {
          if (input.files) {
            let filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
              let reader = new FileReader();
              reader.onload = function(event) {
                $($.parseHTML("<img>"))
                  .attr("src", event.target.result)
                  .attr("class", "w-75")
                  .appendTo(placeToInsertImagePreview);
              };
              reader.readAsDataURL(input.files[i]);
            }
          }
        };
        $("#formFile").on("change", function() {
          imagesPreview(this, "div.preview-images");
        });
      });
</script>