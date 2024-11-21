<?php 
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");

$conn = new mysqli("localhost:3306", "Sudoku", "", "Sudoku");

if ($conn->connect_error) {
    die("{\"status\": \"fail\", \"message\": \"Failed to connect to the database: " . $conn->connect_error . "\"}");
}

$set = $conn->query("SELECT SSO.Users.username, SSO.Users.fullName, SSO.Users.pfpPath, UserData.puzzlesSolved FROM UserData JOIN SSO.Users ON SSO.Users.id = UserData.id ORDER BY UserData.puzzlesSolved DESC LIMIT 5;");
$res = [];

while ($r = $set->fetch_assoc()) {
    array_push($res, $r);
}

return json_encode($res);
?>
