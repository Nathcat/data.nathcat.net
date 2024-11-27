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

if ($_SESSION["user"]["passwordUpdated"]) : ?>

<div class="user-view-container">
    <div style="grid-area: user-data; width: 100%;" class="column justify-center align-center">
        <h1>Welcome, <?php echo $_SESSION["user"]["fullName"]; ?>.</h1>

        <div class="profile-picture">
            <img src="<?php echo "/pfps/" . $_SESSION["user"]["pfpPath"]; ?>">
        </div>

        <form class="row align-center" method="POST" enctype="multipart/form-data">
            <input type="file" name="uploadFilePFP" />
            <input type="submit" name="uploadPFP" value="Upload new profile picture" />
        </form>

        <div class="content-card" style="width: 100%;">
            <h2>User information</h2>
            <p>Username: <?php echo $_SESSION["user"]["username"] ?></h1></p>
            <p>Email: <?php echo $_SESSION["user"]["email"] ?></h1></p>
            <p>Verified: <?php echo $_SESSION["user"]["verified"] == 1 ? "Yes" : "No, <a href='verify'>Click here to verify</a>" ?></p>
            <a href="docs/policies/privacy-policy.php">View our privacy policy</a>
            <div class="row">
                <button onclick="sso_create_quick_auth()">Save my login info on this browser</button>
                <button onclick="sso_revoke_quick_auth(<?php echo $_SESSION['user']['id']; ?>)">Revoke all sessions</button>
            </div>
        </div>

        <button style="width: 100%;" onclick="var xhr = new XMLHttpRequest(); xhr.onload = function() { location.reload(); }; xhr.open('GET', 'logout.php', true); xhr.send();">Logout</button>
    </div>

    <span></span>
    
    <div style="grid-area: user-search; width: 100%;">
        <div class="content-card column justify-center">
            <h2>User search</h2>
            <input type="text" id="search-username" placeholder="Username..." />
            <input type="text" id="search-fullname" placeholder="Full name..." />
            <button onclick="user_search('search-username', 'search-fullname', 'search-results')">Search</button>
            <div id="search-results" class="column justify-center"></div>

            <script>
                document.getElementById("search-username").addEventListener("keypress", (e) => { 
                    if (e.key == "Enter") {
                        user_search('search-username', 'search-fullname', 'search-results');
                    } 
                });

                document.getElementById("search-fullname").addEventListener("keypress", (e) => { 
                    if (e.key == "Enter") {
                        user_search('search-username', 'search-fullname', 'search-results');
                    } 
                });
            </script>
        </div>
    </div>
</div>

<?php else : ?>
<div class="error-card">
    <h2>Password update!</h2>
    <p>
        In response to feedback on the security of the password system in place on AuthCat, the password system has been updated and improved,
        however this means that all users are required to update their passwords.
    </p>
    <p>
        You can put the same password in again if you wish, although it is of course suggested that you choose a new, more secure password.
    </p>
</div>

<div class="column align-center justify-center">
    <input type="password" id="new-password" placeholder="Enter your new password..." />
    <input type="password" id="new-password2" placeholder="Repeat your new password..." />
    <button onclick="sso_update_password(document.getElementById('new-password').value, document.getElementById('new-password2').value)">Submit new password</button>
</div>

<script>
    document.getElementById("new-password").addEventListener("keypress", (e) => { 
        if (e.key == "Enter") {
            sso_update_password(document.getElementById('new-password').value, document.getElementById('new-password2').value)
        } 
    });

    document.getElementById("new-password2").addEventListener("keypress", (e) => { 
        if (e.key == "Enter") {
            sso_update_password(document.getElementById('new-password').value, document.getElementById('new-password2').value)
        } 
    });
</script>

<?php endif; ?>