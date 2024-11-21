<?php
include("../sso/start-session.php");

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: https://sudoku.nathcat.net");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");

if (!array_key_exists("user", $_SESSION)) {
    die("{\"status\": \"fail\", \"message\": \"Not logged in.\"}");
}

$conn = new mysqli("localhost:3306", "Sudoku", "", "Sudoku");
$stmt = $conn->prepare("SELECT * FROM UserData WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user"]["id"]);
$stmt->execute(); $res = $stmt->get_result()->fetch_assoc();

$stmt->close();
$conn->close();
echo json_encode($res);
?>
