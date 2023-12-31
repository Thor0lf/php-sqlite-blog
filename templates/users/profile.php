<?php 
use App\Database\Database;
use App\Users\ModifyProfileUser;

require '../vendor/autoload.php';

$auth = Database::getAuth();
$user = $auth->user();

Database::getAuth()->requireRole('user', 'admin');

$pageTitle = "Profil utilisateur";

$updateUserForm = new ModifyProfileUser();
$updateUserForm->validateForm();
$updateUserForm->processForm();

?>

<?php if ($user->email === $_SESSION['auth_email']): ?>

    <?php if ($updateUserForm->getError()): ?>
        <div class="alert alert-danger">
            <?= $updateUserForm->getError() ?>
        </div>
    <?php endif ?>

    <div class="col-sm-6 mx-auto my-3">
        <div class="card text-center">
            <div class="card-header bg-secondary bg-opacity-50">
                <h3>Modifier son profil</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group mb-2">
                        <input type="text" name="fullname" class="form-control" placeholder="Nom complet" value="<?= htmlentities($user->fullname) ?>" autofocus>
                    </div>
                    <div class="form-group mb-2">
                        <input type="email" name="email" class="form-control" placeholder="Adresse email" value="<?= htmlentities($user->email) ?>">
                    </div>
                   <a href="/change-password" class="change-password">Changer mot de passe</a>
                    <div class="form-group d-md-block justify-content-center mt-3">
                        <button class="col-2 btn btn-primary btn-block me-3 btn-save" type="submit"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Mettre à jour l'utilisateur">
                            <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                        <!-- Button trigger modal -->
                        <span data-bs-toggle="modal" data-bs-target="#modalUserProfile<?= $user->id ?>">
                            <button type="button" class="col-2 btn btn-outline-danger btn-block ms-3" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Supprimer l'utilisateur">
                                <i class="fa-solid fa-trash" aria-hidden="true"></i>
                            </button>
                        </span>

                        <!-- Modal -->
                        <div class="modal fade" id="modalUserProfile<?= $user->id ?>" tabindex="-1" aria-labelledby="modalLabelUser" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h2 class="modal-title fs-5" id="modalLabelUserProfile"><?= $user->fullname ?></h2>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Voulez-vous vraiment supprimer cet utilisateur ?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <a href="/delete-user" type="button" class="btn btn-danger">Supprimer</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php else: header('Location: /403') ?>
<?php endif ?>