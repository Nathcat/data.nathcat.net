<script>
    function login_form_callback(response) {
        let fd = new FormData();

        console.log(response);
        
        if (response.status === "success") {
            fd.set("user", JSON.stringify(response.user));
        }
        else {
            fd.set("login-error", response.message);
        }
        
        fetch("login.php", {
            method: "POST",
            body: fd
        })
        .then((r) => { location.reload(); });
    }
</script>

<div class="column align-center">
    <input id="login-username" type="text" name="username" placeholder="Enter username..." />
    <input id="login-password" type="password" name="password" placeholder="Enter password..." />
    <button onclick="sso_try_login(document.getElementById('login-username').value, document.getElementById('login-password').value, login_form_callback);">Login</button>
    <a href="<?php echo dirname($_SERVER["PHP_SELF"]); ?>?newUser">Or, create a new user</a>
</div>

<script>
    let login_enter_callback = (event) => {
        if (event.key === "Enter") {
            sso_try_login(document.getElementById('login-username').value, document.getElementById('login-password').value, login_form_callback);
        }
    };

    document.getElementById("login-username").addEventListener("keypress", login_enter_callback);
    document.getElementById("login-password").addEventListener("keypress", login_enter_callback);
</script>