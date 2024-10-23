const baseURL = "https://data.nathcat.net";

function sso_try_login(username, password, callback) {

    let fd = new FormData();
    fd.set("username", username);
    fd.set("password", password);

    fetch(baseURL + "/sso/try-login.php", {
        method: "POST",
        body: fd
    })
    .then((response) => response.json())
    .then(callback)
    .catch((error) => {
        console.log("SSO Request failed: " + error);
    });
}

function sso_create_new_user(username, email, password, password2, fullName, callback) {
    let fd = new FormData();
    fd.set("username", username);
    fd.set("email", email);
    fd.set("password", password);
    fd.set("password2", password2);
    fd.set("fullName", fullName);
    fetch(baseURL + "/sso/create-user.php", {
        method: "POST",
        body: fd
    })
    .then((r) => r.json())
    .then(callback);
}