<?php

use App\RegistrationForm;

require '../vendor/autoload.php';

$pageTitle = "Page d'enregistrement";

$registrationForm = new RegistrationForm();
$registrationForm->validateForm();
$registrationForm->processForm();

?>

<?php dump($_SERVER) ?>
<?php dump($_SESSION) ?>
<?php if ($registrationForm->getError()): ?>
    <div class="alert alert-danger">
        <?= $registrationForm->getError() ?>
    </div>
<?php endif ?>

<div class="col-md-6 mx-auto my-3">
        <div class="card text-center">
            <div class="card-header bg-secondary bg-opacity-50">
                <h3>Nouvel utilisateur</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="isHuman">
                    <input type="hidden" name="_csrfToken" value={{csrf}}>
                    <div class="form-group mb-2">
                        <input type="text" name="fullname" class="form-control" placeholder="Nom complet" value="<?= htmlentities($fullname ?? '') ?>" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <input type="email" name="email" class="form-control" id="email_signup" placeholder="Adresse email" value="<?= htmlentities($email ?? '') ?>">
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group" id="show_hide_password_signup">
                            <input type="password" name="password" class="form-control" placeholder="Mot de passe">
                            <span class="input-group-text"><a href="" tabindex="98"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                        <div class="form-text text-danger">Minimum 1 majuscule, 1 minuscule, 1 chiffre et 1 symbole.</div>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group" id="show_hide_confirm_password_signup">
                            <input type="password" name="confirm_password" class="form-control" placeholder="Confirmation du mot de passe">
                            <span class="input-group-text"><a href="" tabindex="99"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <button class="d-grid col-12 btn btn-primary btn-block" type="submit">
                            S'enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>