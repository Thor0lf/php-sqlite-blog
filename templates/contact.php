<?php 
    
    use App\ContactFormHandler;
    use App\Database;

    require '../vendor/autoload.php';
    
    $pageTitle = 'Page de contact';

    $formHandler = new ContactFormHandler();
    $formHandler->handleFormSubmission();
?>

    <?php if ($formHandler->getError()): ?>
        <div class="alert alert-danger">
            <?= $formHandler->getError() ?>
        </div>
    <?php endif ?>
        <form action="contact" method="POST">
            <div class="card mb-3 mx-auto" style="max-width: 800px;">
                <div class="row g-0">
                    <div class="col-md-4 bg-secondary rounded-start">
                        <img src="/img/contact.jpeg" class="d-none d-md-block img-fluid rounded-start h-100" alt="contact">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title mb-5">Formulaire de contact</h5>
                            <input type="hidden" name="isHuman">
                            <div class="card-text">
                                <?php if ($formHandler->getSuccess()): ?>
                                    <div class="alert alert-success">
                                        <?= $formHandler->getSuccess() ?>
                                    </div>
                                <?php else: ?>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInputName" name="name" value="<?= htmlentities($_POST['name'] ?? '') ?>" placeholder="Votre nom">
                                    <label for="floatingInputName">Votre nom</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="email" class="form-control" id="floatingInputEmail" name="email" value="<?= htmlentities($_POST['email'] ?? '') ?>" placeholder="name@example.com">
                                    <label for="floatingInputEmail">Votre adresse email</label>
                                </div>

                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInputMessage" name="subject" value="<?= htmlentities($_POST['subject'] ?? '') ?>" placeholder="Sujet de votre message">
                                    <label for="floatingInputMessage">Sujet du message</label>
                                </div>

                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Votre commentaire" name="content" id="floatingTextarea" style="height: 100px"><?= htmlentities($_POST['content'] ?? '') ?></textarea>
                                    <label for="floatingTextarea">Contenu du message</label>
                                </div>

                                <div class="text-center mt-5">
                                    <button class="btn btn-primary btn-block" type="submit" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Envoyer le message">
                                        Envoyer
                                    </button>
                                </div>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
