<?php 
header("Location: " . sprintf("%s://%s", 
isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off" ? "https" : "http",
$_SERVER["SERVER_NAME"]
)); 
?>