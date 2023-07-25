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