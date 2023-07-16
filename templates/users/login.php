<?php

use App\Database;

require '../vendor/autoload.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = "Page de connexion";

$auth = Database::getAuth();
$auth->alreadySignin();
$auth->signin();

$referer = $_SERVER['HTTP_REFERER'] ?? '';
$referer = substr($referer, 21);
if ($referer != '/' && !empty($referer)) { 
    $referer = substr($referer, 1);
}
if (empty($referer) || $referer == '/login') {
    $referer == '/'; 
}

?>

    <?php if (isset($_GET['forbid'])): ?>
        <div class="alert alert-danger">
            Accès interdit
        </div>
    <?php endif ?>

    <div class="col-md-6 mx-auto my-3">
        <div class="card text-center">
            <div class="card-header bg-secondary bg-opacity-50">
                <h3>Écran de connexion</h3>
            </div>
            <img src="/img/logo_signin.png" class="rounded-circle mx-auto d-block m-4 w-25">
            <div class="card-body">
                <form action="" method="post">
                <input type="hidden" name="referer" value="<?= $referer ?>">
                    <div class="form-group mb-3 d-grid col-12">
                        <label for="email">
                        <input type="email" name="email" class="form-control" id="email" placeholder="Adresse email" autofocus>
                    </div>
                    <div class="form-group mb-3">
                        <div class="input-group" id="show_hide_password_signin">
                            <input type="password" name="password" class="form-control" placeholder="Mot de passe">
                            <span class="input-group-text"><a href="" tabindex="99"><i class="fa fa-eye-slash" aria-hidden="true"></i></a></span>
                        </div>
                    </div>
                    <div class="form-group mb-3 d-flex justify-content-between">
                        <div>
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="rememberMe">
                            <label class="form-check-label" for="rememberMe">
                                Rester connecté
                            </label>
                        </div>
                        <a href="forgot-password">Mot de passe oublié ?</a>
                    </div>
                    <div class="form-group">
                        <button class="d-grid col-12 btn btn-primary btn-block" type="submit">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
