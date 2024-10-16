<script>
    function create_new_user_callback(response) {

        if (response.status == "success") {
            window.location = "<?php echo dirname($_SERVER["PHP_SELF"]); ?>";
        } 
        else {
            alert(response.message);
        }
    }
</script>

<div class="column align-center">
    <input id="login-username" type="text" name="username" placeholder="Enter username..." />
    <input id="login-email" type="email" name="email" placeholder="Enter your email..." />
    <input id="login-password" type="password" name="password" placeholder="Enter password..." />
    <input id="login-password2" type="password" name="password2" placeholder="Re-enter password..." />
    <input id="login-fullName" type="text" name="fullName" placeholder="Enter your name..." />
    <button onclick='sso_create_new_user($("#login-username").val(), $("#login-email").val(), $("#login-password").val(), $("#login-password2").val(), $("#login-fullName").val(), create_new_user_callback);'>Create new user</button>
    <a href="<?php echo dirname($_SERVER["PHP_SELF"]); ?>">Or, login</a>
</div>

<script>
    function new_user_enter(event) {
        if (event.key === "Enter") {
            sso_create_new_user($("#login-username").val(), $("#login-email").val(), $("#login-password").val(), $("#login-password2").val(), $("#login-fullName").val(), create_new_user_callback);
        }
    }

    document.getElementById("login-username").addEventListener("keypress", new_user_enter);
    document.getElementById("login-password").addEventListener("keypress", new_user_enter);
    document.getElementById("login-password2").addEventListener("keypress", new_user_enter);
    document.getElementById("login-email").addEventListener("keypress", new_user_enter);
    document.getElementById("login-fullName").addEventListener("keypress", new_user_enter);
</script>