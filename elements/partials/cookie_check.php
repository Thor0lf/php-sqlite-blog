<?php

// Check if the cookie exists
if (!isset($_COOKIE['cookieConsent']) || $_COOKIE['cookieConsent'] !== 'ok') {
    echo '<script>
    // To open the offcanvas of Bootstrap when the page is loading
    window.onload = function() {
        var myOffcanvas = new bootstrap.Offcanvas(document.getElementById(\'offcanvasWithBothOptions\'));
        myOffcanvas.show();
    };
    </script>';
}

// Check if the form button has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accept_cookies'])) {
    setcookie('cookieConsent', 'ok', time() + (365 * 24 * 60 * 60), '/');
    // Set the cookie with a duration of one year
    header("Location: /");
    exit();
}

?>

<button type="button" class="btn btn-none btn-cookie" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions"><i class="fa-solid fa-cookie fa-2xl"></i></button>

<div class="offcanvas offcanvas-bottom offcanvas-cookie" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Utilisation des cookies</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
        <p>Ce site utilise des cookies obligatoires pour son bon fonctionnement. Veuillez cliquer sur le bouton ci-dessous pour accepter l'utilisation des cookies :</p>
        <form action="" method="post" class="d-flex justify-content-evenly">
            <a href="/cookies" class="btn btn-outline-secondary border-0">En savoir plus</a>
            <button type="submit" name="accept_cookies" class="btn btn-dark">Je comprends</button>
        </form>
    </div>
</div>