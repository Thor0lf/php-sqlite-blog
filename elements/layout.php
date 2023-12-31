<?php 
use App\Helpers\FlashMessage;
require 'partials/head.php';
include 'partials/cookie_check.php';
require 'partials/navigation.php'; 
?>

<div class="container mt-4">
    <?= FlashMessage::getFlashMessage() ?>
    <?= $pageContent ?>
</div>
    
<?php
require 'partials/footer.php';
require 'partials/end.php';
?>
        