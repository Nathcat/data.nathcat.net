async function sso_try_login(username, password, callback) {
    fetch("https://data.nathcat.net/sso/try-login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            "username": username,
            "password": password
        })
    })
    .then((response) => response.json())
    .then(callback);
}