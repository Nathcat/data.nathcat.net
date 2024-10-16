async function sso_try_login(username, password, callback) {

    let fd = new FormData();
    fd.set("username", username);
    fd.set("password", password);

    fetch("https://data.nathcat.net/sso/try-login.php", {
        method: "POST",
        body: fd
    })
    .then((response) => response.json())
    .then(callback)
    .catch((error) => {
        console.log("SSO Request failed: " + error);
    });
}