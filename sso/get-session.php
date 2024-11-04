<?php
include("start-session.php");

header("Content-Type: application/json");

echo json_encode($_SESSION);
?>