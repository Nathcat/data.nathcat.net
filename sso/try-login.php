<?php
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$DEBUG = $_GET["DEBUG"];
if ($DEBUG) {
    echo "<p>In debug mode.</p>";
}

if (!(array_key_exists("username", $_POST) && array_key_exists("password", $_POST))) {
    echo "{\"status\": \"fail\", \"message\": \"Invalid request.\"}";
    die();
}

else if ($_POST["username"] == "" || $_POST["password"] == "") {
    echo "{\"status\": \"fail\", \"message\": \"Please provide both username and password.\"}";
    die();
}

if ($DBEUG) {
    echo "<p>Username: " . $_POST["username"] . "<br>Password: " . $_POST["password"] . "</p>"; 
}

$DB_server = "localhost:3306";
$DB_user = "sso";
$DB_pass = "";
$DB_schema = "SSO";

$conn = new mysqli($DB_server, $DB_user, $DB_pass, $DB_schema);

if ($conn->connect_error) {
    echo "{\"status\": \"fail\", \"message\": \"Failed to connect to the database: " . $conn->connect_error . "\"}";
    die();
}

$stmt = $conn->prepare("SELECT * FROM Users WHERE username LIKE ?");
$stmt->bind_param("s", $_POST["username"]);
$stmt->execute();
$result = $stmt->get_result();

$pass_hash = hash("sha256", $_POST["password"]);
$DB_r = $result->fetch_assoc();

if ($DB_r["password"] == $pass_hash) {
    echo "{\"status\": \"success\", \"user\": " . json_encode($DB_r) . "}";
}
else {
    echo "{\"status\": \"fail\", \"message\": \"Incorrect username / password combination.\"}";
}

$conn->close();
?>