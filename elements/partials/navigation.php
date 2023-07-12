<?php

use App\CategoriesListing;
use App\Database;
use App\UsersListing;

$auth = Database::getAuth();
$user = $auth->user();

$numberOfUsers = new UsersListing();
$numberofCategories = new CategoriesListing();

?>

<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
  <div class="container-fluid">
    <a href="/" class="navbar-logo"><img src="/img/logo.png" alt="logo" width="100%" height="100%"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <div class="collapse navbar-collapse d-flex justify-content-between">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="/">Accueil</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="contact">Contact</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <?php if ($user) : ?>
            <li class="btn-group dropstart">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Bienvenue <?= htmlentities($user->fullname) ?>
              </a>
              <ul class="dropdown-menu">
                <!-- Admin only -->
                <?php if ($user->role === 'admin') : ?>
                  <li><a class="dropdown-item" href="/categories-list"><i class="fa-solid fa-folder-tree me-2"></i>Liste des catégories</a></li>
                  <li><a class="dropdown-item" href="/add-edit-category"><i class="fa-solid fa-folder-plus me-2"></i>Ajouter une catégorie</a></li>
                  <?php if ($numberofCategories->getTotalCategories() > 0) : ?>
                    <li>
                      <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="/articles-list"><i class="fa-solid fa-rectangle-list me-2"></i>Liste des articles</a></li>
                    <li><a class="dropdown-item" href="/add-edit-article"><i class="fa-solid fa-square-plus me-2"></i>Ajouter un article</a></li>
                  <?php endif ?>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li><a class="dropdown-item" href="/users-list"><i class="fa-solid fa-users me-2"></i>Liste des utilisateurs</a></li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                <?php endif ?>
                <!-- User connected -->
                <?php if ($user->role === 'admin' || 'user') : ?>
                  <li><a class="dropdown-item" href="profile"><i class="fa-solid fa-user-gear me-2"></i>Profil utilisateur</a></li>
                  <li>
                    <hr class="dropdown-divider" />
                  </li>
                  <li><a class="dropdown-item" href="logout"><i class="fa-solid fa-right-from-bracket me-2"></i>Se déconnecter</a></li>
              </ul>
            </li>
          <?php endif ?>
        <?php else : ?>
          <?php if ($numberOfUsers->getTotalUsers() > 0) : ?>
            <li class="nav-item">
              <div class="nav-link"><a href="login" class="btn btn-md btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Se connecter"><i class="fa-solid fa-circle-user"></i></a></div>
            </li>
          <?php endif ?>
          <li class="nav-item">
            <div class="nav-link"><a href="signup" class="btn btn-md btn-dark" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="S'enregistrer"><i class="fa-solid fa-user-plus"></i></a></div>
          </li>
        <?php endif ?>
        </ul>
      </div>
    </div>
  </div>
</nav>