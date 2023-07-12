<?php

require '../vendor/autoload.php';

use App\Database;
use App\UsersListing;

Database::getAuth()->requireRole('admin');

$pageTitle = "Liste des utilisateurs";

$currentPage = htmlentities($_GET['page'] ?? 1) ;
$usersPerPage = 10;

$usersListing = new UsersListing($currentPage, $usersPerPage);
$users = $usersListing->getAllUsers();
$totalUsers = $usersListing->getTotalUsers();
$totalPages = $usersListing->getTotalPages();
?>

<?php if ($usersListing->getError()): ?>
    <div class="alert alert-danger">
        <?= $usersListing->getError() ?>
    </div>
<?php endif ?>

  <div class="pb-2 mt-4 mb-2 border-bottom text-center">
    <h2>Liste des utilisateurs
      <a
        class="btn btn-info float-right btn-sm"
        href="/signup"
      ><i class="fa fa-plus"></i> Créer un utilisateur</a>
    </h2>
    <h6 class="mt-4">Nombre total d'utilisateurs : <?= $totalUsers ?></h6>
  </div>
  <div class="row">
    <div class="col">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Nom complet <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Email <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Rôle <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Date de création <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th>Date de modification <i class="fa-solid fa-right-left fa-rotate-90"></i></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
              <td><?= $user->id ?></td>
              <td><?= $user->fullname ?></td>
              <td><?= $user->email ?></td>
              <td><?= $user->role ?></td>
              <td><?= $user->created_at ?></td>
              <td><?= $user->updated_at ?></td>
              <td class="text-center">
                <!-- Button trigger modal -->
                <span data-bs-toggle="modal" data-bs-target="#modalUser<?= $user->id ?>">
                  <button type="button" class="btn mb-1 fa-solid fa-trash" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Supprimer l'utilisateur">
                </button></span>

                <!-- Modal -->
                <div class="modal fade" id="modalUser<?= $user->id ?>" tabindex="-1" aria-labelledby="modalLabelUser" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h2 class="modal-title fs-5" id="modalLabelUser"><?= $user->fullname ?></h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        Voulez-vous vraiment supprimer cet utilisateur ?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <a href="/delete-user?email=<?= $user->email ?>" type="button" class="btn btn-danger">Supprimer</a>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
  </div>


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
