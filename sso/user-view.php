<?php 
if (isset($_POST["uploadPFP"])) {
    $filename = $_FILES["uploadPFPFile"]["name"];
    $tempname = $_FILES["uploadPFPFile"]["tmp_name"];
    $folder = "../pfps/" . $filename;

    if (move_uploaded_file($tempname, $folder)) {
        $conn = new mysqli("localhost", "sso", "", "SSO");
        $stmt = $conn->prepare("UPDATE Users SET pfpPath = ? WHERE id = ?");
        $stmt->bind_param("si", $filename, $_SESSION["user"]["id"]);
        $stmt->execute();
        $conn->close();
        $_SESSION["user"]["pfpPath"] = $filename;
        echo "<div class='content-card'><h2>Uploaded profile picture.</h2></div>";
    }
    else {
        echo "<div class='error-card'><h2>Failed to upload profile picture!</h2></div>";
    }
}
?>

<h1>Welcome, <?php echo $_SESSION["user"]["fullName"] ?>.</h1>

<div class="profile-picture">
    <img src="<?php echo "https://data.nathcat.net/pfps/" . $_SESSION["user"]["pfpPath"]; ?>">
</div>

<form class="row" method="POST" action="">
    <input type="file" name="uploadPFPFile" />
    <input type="submit" name="uploadPFP" />
</form>

<div class="content-card">
    <h2>User information</h2>
    <p>Username: <?php echo $_SESSION["user"]["username"] ?></h1></p>
    <p>Email: <?php echo $_SESSION["user"]["email"] ?></h1></p>
</div>

<button onclick="var xhr = new XMLHttpRequest(); xhr.onload = function() { location.reload(); }; xhr.open('GET', 'logout.php', true); xhr.send();">Logout</button>