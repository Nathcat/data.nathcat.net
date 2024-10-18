<?php
session_name("AuthCat-SSO");
session_set_cookie_params(0, '/', ".nathcat.net");
session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["CONTENT_TYPE"] == "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}

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
    $stmt->close();
}
catch (Exception $e){
    $conn->close();
    die("{\"status\": \"fail\", \"message\": \"The username " . $_POST["username"] . " is already in use.\"}");
}

try {
    $stmt = $conn->prepare("SELECT id FROM Users WHERE username like ?");
    $stmt->bind_param("s", $_POST["username"]);
    $stmt->execute();
    $res = $stmt->get_result();
    $id = $res->fetch_assoc()["id"];

    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO Mailer.MailToSend (recipient, subject, content) VALUES (?, \"Welcome!\", \"Dear \$fullName\$,\n\nWelcome to the Nathcat network!\n\nBest wishes,\nNathan.\")");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "{\"status\": \"success\"}";
}
catch (Exception $e) {
    echo "{\"status\": \"fail\", \"message\": \"User was created but failed to create new user email notification: " . $e->getMessage() . "\"}";
}

$conn->close();
?>