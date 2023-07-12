<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="Thomas Audouin">
    <meta name="description" content="Ceci est la version en PHP avec SQLite de mon projet à présenter au jury pour le passage Titre Professionnel Développeur Web & Web Mobile">
    <meta name="keywords" content="<?= htmlentities($keywords ?? '') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lorem Ipsum - <?= htmlentities($pageTitle ?? '') ?></title>
    <link rel="icon" type="image/png" href="/img/favicon.png">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.2.3/css/bootstrap.min.css" integrity="sha512-SbiR/eusphKoMVVXysTKG/7VseWii+Y3FdHrt0EpKgpToZeemhqHeZeLWLhJutz/2ut2Vw1uQEj2MbRF+TVBUA==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <!-- Custom Css -->
    <link rel="stylesheet" href="/css/style.css">
    <!-- Text Editor -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/ui/trumbowyg.min.css" integrity="sha512-Zi7Hb6P4D2nWzFhzFeyk4hzWxBu/dttyPIw/ZqvtIkxpe/oCAYXs7+tjVhIDASEJiU3lwSkAZ9szA3ss3W0Vug==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.2/jquery.min.js" integrity="sha512-tWHlutFnuG0C6nQRlpvrEhE4QpkG1nn2MOUMWmUeRePl4e3Aki0VB6W1v3oLjFtd0hVOtRQ9PHpSfN6u6/QXkQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/trumbowyg.min.js" integrity="sha512-ZfWLe+ZoWpbVvORQllwYHfi9jNHUMvXR4QhjL1I6IRPXkab2Rquag6R0Sc1SWUYTj20yPEVqmvCVkxLsDC3CRQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/langs/fr.min.js" integrity="sha512-Lm4FmZmqh2vXcK+zMhscAMdwkYtobg+0oKS5gIA38zOfeuXGte+7Xvcm5yViyrea4iNgKTQlsDu/NLaIaRlvuA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.26.0/plugins/upload/trumbowyg.upload.min.js" integrity="sha512-tblyvFBkJg7Wlsx8tE+bj1HhrMSP4BtbeMNBoWlu2EtqZW24x52TZoP1ueepV4UbKfFz67Nsjucw++2Joju/nA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body>
    <main>
    <div id="top"></div>
    <a href="#top" type="button" class="btn btn-secondary back-to-top" title="Retour en haut">
        <i class="fa fa-arrow-up"></i>
    </a>