<?php
use App\Users\VerifyUser;

require ('../vendor/autoload.php');

$verify = new VerifyUser();

if ($_GET['token']) {
    $verify->verifyUserAfterRegisterEmail();
}
if ($_GET['token-fp']) {
    $verify->verifyUserBeforeResetPassword();
}

?>