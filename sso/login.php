<?php
$session_name = session_name("AuthCat-SSO");
session_set_cookie_params(0, '/', 'nathcat.net');
session_start();

if ($_POST["user"]) {
    $_SESSION["user"] = json_decode($_POST["user"], true);
    unset($_SESSION["login-error"]);
}
else {
    $_SESSION["login-error"] = $_POST["login-error"];
}
?>