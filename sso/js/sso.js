const SSO_BASE_URL = "https://data.nathcat.net";

function sso_try_login(username, password, callback) {

    let fd = new FormData();
    fd.set("username", username);
    fd.set("password", password);

    fetch(SSO_BASE_URL + "/sso/try-login.php", {
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
    fetch(SSO_BASE_URL + "/sso/create-user.php", {
        method: "POST",
        body: fd
    })
    .then((r) => r.json())
    .then(callback);
}

function sso_update_password(new_password, password_reentry) {
    if (new_password === "" || password_reentry === "") {
        alert("One or more fields are empty!");
    }
    else if (new_password !== password_reentry) {
        alert("Passwords do not match!");
    }
    else {

        let fd = new FormData();
        fd.set("password", new_password);

        fetch(SSO_BASE_URL + "/sso/change-password.php", {
            method: "POST",
            body: fd
        }).then((r) => location.reload());

    }
}