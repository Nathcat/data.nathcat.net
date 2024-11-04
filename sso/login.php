<?php
include("start-session.php");

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

if ($_POST["user"]) {
    $_SESSION["user"] = json_decode($_POST["user"], true);
    unset($_SESSION["login-error"]);
}
else {
    $_SESSION["login-error"] = $_POST["login-error"];
}
?>