<?php 
include("start-session.php");

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER["CONTENT_TYPE"] == "application/json") {
    $_POST = json_decode(file_get_contents("php://input"), true);
}
else if ($_SERVER["CONTENT_TYPE"] != "multipart/form-data") {
    die("{\"state\": \"fail\", \"message\": \"Invalid request\"}");
}

$results = [];
$conn = new mysqli("localhost:3306", "sso", "", "SSO");

if ($conn->connect_error) {
    die("{\"state\": \"fail\", \"message\": \"" . $conn->connect_error . "\"}");
}

if (array_key_exists("username", $_POST) && $_POST["username"] != "") {
    $stmt = $conn->prepare("SELECT id, username, fullName FROM Users WHERE username LIKE ?");
    $username_pattern = $_POST["username"] . "%";
    $stmt->bind_param("s", $username_pattern);
    $stmt->execute(); $res_set = $stmt->get_result();

    while ($res = $res_set->fetch_assoc()) {
        $results[$res["id"]] = $res;
    }
    
    $stmt->close();
}

if (array_key_exists("fullName", $_POST) && $_POST["fullName"] != "") {
    $stmt = $conn->prepare("SELECT id, username, fullName FROM Users WHERE fullName LIKE ?");
    $fullName_pattern = $_POST["fullName"] . "%";
    $stmt->bind_param("s", $fullName_pattern);
    $stmt->execute(); $res_set = $stmt->get_result();

    while ($res = $res_set->fetch_assoc()) {
        $results[$res["id"]] = $res;
    }

    $stmt->close();
}

$conn->close();

$o = [
    "state" => "success",
    "results" => $results
];

echo json_encode($o);

?>