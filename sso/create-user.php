<?php
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$DEBUG = $_GET["DEBUG"];
if ($DEBUG) {
    echo "<p>In debug mode.</p>";
    print_r($_POST);
}

if (!(array_key_exists("username", $_POST) && array_key_exists("email", $_POST) && array_key_exists("password", $_POST) && array_key_exists("password2", $_POST) && array_key_exists("fullName", $_POST))) {
    die("{\"status\": \"fail\", \"message\": \"Invalid request.\"}");
}
else if ($_POST["username"] == "" || $_POST["email"] == "" || $_POST["password"] == "" || $_POST["password2"] == "" || $_POST["fullName"] == "") {
    die("{\"status\": \"fail\", \"message\": \"Please do not leave any fields blank.\"}");
}
else if ($_POST["password"] != $_POST["password2"]) {
    die("{\"status\": \"fail\", \"message\": \"Passwords don't match.\"}");
}

$DB_server = "localhost:3306";
$DB_user = "sso";
$DB_pass = "";
$DB_schema = "SSO";

$conn = new mysqli($DB_server, $DB_user, $DB_pass, $DB_schema);

if ($conn->connect_error) {
    die("{\"status\": \"fail\", \"message\": \"Failed to connect to the database: " . $conn->connect_error . "\"}");
}

$stmt = $conn->prepare("INSERT INTO Users (username, email, fullName, password) VALUES (?, ?, ?, SHA2(?, 256))");
$stmt->bind_param("ssss", $_POST["username"], $_POST["email"], $_POST["fullName"], $_POST["password"]);
try {
    $stmt->execute();
    echo "{\"status\": \"success\"}";
}
catch (Exception $e){
    echo "{\"status\": \"fail\", \"message\": \"" . $e->getMessage() . "\"}";
}

$conn->close();
?>