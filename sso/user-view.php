<?php 
if (isset($_POST["uploadPFP"])) {
    $new_file = "../pfps/" . $_SESSION["user"]["id"] . ".png";
    
    if ($_FILES["uploadFilePFP"]["size"] != 0) {
        if (getimagesize($_FILES["uploadFilePFP"]["tmp_name"]) !== false) {  // This checks if the file is a valid image
            if (move_uploaded_file($_FILES["uploadFilePFP"]["tmp_name"], $new_file)) {
                echo "<div class='content-card'><h2>Uploaded profile picture</h2></div>";

                try {
                    $conn = new mysqli("localhost:3306", "sso", "", "SSO");
                    $stmt = $conn->prepare("UPDATE Users SET pfpPath = ? WHERE id = ?");
                    $new_file_name = $_SESSION["user"]["id"] . ".png";
                    $stmt->bind_param("sd", $new_file_name, $_SESSION["user"]["id"]);
                    $stmt->execute();
                    $conn->close();

                    $_SESSION["user"]["pfpPath"] = $new_file_name;
                } catch (Exception $e) {
                    echo "<div class='error-card'><h2>Failed to upload profile picture!</h2><p>" . $e->getMessage() . "</p></div>";
                }
            }
            else {
                echo "<div class='error-card'><h2>Failed to upload profile picture!</h2></div>";
            }
        }
        else {
            echo "<div class='error-card'><h2>Please select an image file!</h2></div>";
        }
    }
    else {
        echo "<div class='error-card'><h2>Please select a file!</h2></div>";
    }
}
?>
<div style="width: 100%;" class="row align-center justify-center">
    <div class="column align-center justify-center">
        <h1>Welcome, <?php echo $_SESSION["user"]["fullName"]; ?>.</h1>

        <div class="profile-picture">
            <img src="<?php echo "/pfps/" . $_SESSION["user"]["pfpPath"]; ?>">
        </div>

        <form class="row align-center" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploadFilePFP" />
            <input type="submit" name="uploadPFP" value="Upload new profile picture" />
        </form>

        <div class="content-card">
            <h2>User information</h2>
            <p>Username: <?php echo $_SESSION["user"]["username"] ?></h1></p>
            <p>Email: <?php echo $_SESSION["user"]["email"] ?></h1></p>
            <p>Verified: <?php echo $_SESSION["user"]["verified"] == 1 ? "Yes" : "No, <a href='verify'>Click here to verify</a>" ?></p>
            <a href="docs/policies/privacy-policy.php">View our privacy policy</a>
        </div>

        <button onclick="var xhr = new XMLHttpRequest(); xhr.onload = function() { location.reload(); }; xhr.open('GET', 'logout.php', true); xhr.send();">Logout</button>
    </div>

    <span class="quarter-spacer"></span>
    
    <div class="column align-center justify-center">
        <div class="content-card column justify-center">
            <h2>User search</h2>
            <input type="text" id="search-username" placeholder="Username..." />
            <input type="text" id="search-fullname" placeholder="Full name..." />
            <button onclick="user_search('search-username', 'search-fullname', 'search-results')">Search</button>
            <div id="search-results" class="column justify-center"></div>
        </div>
    </div>
</div>