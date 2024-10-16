async function sso_try_login() {
    let r;
    fetch("https://data.nathcat.net/sso/try-login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "username": document.getElementById("login-username").value,
            "password": document.getElementById("login-password").value
        })
    })
    .then((response) => response.json())
    .then((json) => { r = json; });

    return await r;
}