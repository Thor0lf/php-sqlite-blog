<?php
use App\VerifyUser;

require ('../vendor/autoload.php');

$verify = new VerifyUser();
dump($_SERVER);
if ($_GET['token']) {
    $verify->verifyUserAfterRegisterEmail();
}
if ($_GET['token-fp']) {
    $verify->verifyUserBeforeResetPassword();
}

?>