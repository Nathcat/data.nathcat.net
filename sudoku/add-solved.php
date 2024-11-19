<?php
include("../sso/start-session.php");
include("sudoku-utils.php");

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

if (!array_key_exists("user", $_SESSION)) {
    die("{\"status\": \"fail\", \"message\": \"Not logged in.\"}");
}

if (!array_key_exists("HAS_SOLVED_SUDOKU", $_SESSION)) {
    die("{\"status\": \"fail\", \"message\": \"You have not just solved a sudoku.\"}");
}

if ($_SERVER["CONTENT_TYPE"] == "text/plain") {
    $_PUZZLE = file_get_contents("php://input");
    $_PUZZLE = explode("\n", $_PUZZLE);

    // Process the puzzle string into a 2D array
    $_PUZZLE = array_map(function($v) {
        return array_map(function($x) {
            return intval($x);
        }, explode(" ", $v));
    }, $_PUZZLE);
}
else {
    die("{\"status\": \"fail\", \"message\": \"May only pass text/plain with body as Sudoku data.\"}");
}

if (!is_solved($_PUZZLE)) {
    die("{\"status\": \"fail\", \"message\": \"Submitted puzzle is not solved!\"}");
}

$conn = new mysqli("localhost:3306", "Sudoku", "", "Sudoku");

if ($conn->connect_error) {
    die("{\"status\": \"fail\", \"message\": \"Failed to connect to the database: " . $conn->connect_error . "\"}");
}

$stmt = $conn->prepare("INSERT INTO PuzzlesSolved (id) VALUES (?)");
$stmt->bind_param("i", $_SESSION["user"]["id"]);
$stmt->execute(); $stmt->close();

$stmt = $conn->prepare("UPDATE PuzzlesSolved SET count = count + 1 WHERE id = ?");
$stmt->bind_param("i", $_SESSION["user"]["id"]);
$stmt->execute(); $stmt->close();

$conn->close();

echo "{\"status\": \"success\"}";
unset($_SESSION["HAS_SOLVED_SUDOKU"]);
?>