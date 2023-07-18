<?php
use App\Users\ForgotPassword;

require '../vendor/autoload.php';

$pageTitle = "Mot de passe oublié";

$emailSender = new ForgotPassword();
$emailSender->sendPasswordResetEmail();
?>


<div class="container">
    <div class="col-md-4 mx-auto my-3">
        <div class="card text-center">
            <div class="card-header bg-secondary bg-opacity-50">
                <h3>Mot de passe oublié</h3>
            </div>
            <img src="/img/logo_signin.png" class="rounded-circle mx-auto d-block m-4 w-25">
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group mb-3">
                        <label for="inputEmail" class="form-label">Saisissez votre adresse email pour recevoir le lien de réinitialisation du mot de passe.</label>
                        <input type="email" id="inputEmail" name="email" class="form-control" placeholder="Adresse email">
                    </div>
                    <div class="form-group">
                        <button class="d-grid col-12 btn btn-primary btn-block" type="submit">
                            Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>