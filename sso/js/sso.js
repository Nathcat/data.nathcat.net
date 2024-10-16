async function sso_try_login(username, password, callback) {

    let fd = new FormData();
    fd.set("username", username);
    fd.set("password", password);

    fetch("https://data.nathcat.net/sso/try-login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: fd
    })
    .then((response) => response.json())
    .then(callback);
}