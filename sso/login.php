<?php 
session_start();

if ($_POST["user"]) {
    $_SESSION["user"] = json_decode($_POST["user"], true);
    unset($_SESSION["login-error"]);
}
else {
    $_SESSION["login-error"] = $_POST["login-error"];
}
?>