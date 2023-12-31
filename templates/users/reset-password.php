<?php
use App\Users\NewPasswordForm;

require '../vendor/autoload.php';

$pageTitle = "Modification du mot de passe";

$newPasswordForm = new NewPasswordForm();
$newPasswordForm->validateForm();
$newPasswordForm->processForm();

?>

<?php if ($newPasswordForm->getError()): ?>
    <div class="alert alert-danger">
        <?= $newPasswordForm->getError() ?>
    </div>
<?php endif ?>    
    <div class="col-sm-6 mx-auto my-3">
        <div class="card text-center">
            <div class="card-header bg-secondary bg-opacity-50">
                <h3>Modifier son mot de passe</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group mb-2">
                        <div class="input-group" id="show_hide_password_new_password">
                            <input type="password" name="password" class="form-control" placeholder="Nouveau mot de passe">
                            <span class="input-group-text"><a href="" tabindex="98"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group" id="show_hide_password_confirm_new_password">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirmation du nouveau mot de passe">
                            <span class="input-group-text"><a href="" tabindex="99"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                    </div>
                    <div class="form-group d-md-block justify-content-center mt-3">
                        <button class="col-2 btn btn-primary btn-block me-3 btn-save" type="submit"  data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Mettre à jour le mot de passe">
                            <i class="fa-solid fa-floppy-disk"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
