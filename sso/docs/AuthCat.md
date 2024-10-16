# AuthCat

AuthCat provides credential authentication and basic user data for the [nathcat.net](https://nathcat.net) network.

## Implementation

For JS applications, use the following JS file which contains functions which will access the API for you and return the JSON result.
```html
<script src="https://data.nathcat.net/sso/js/sso.js"></script>
```
Otherwise, you can make a `POST` request to `https://data.nathcat.net/sso/try-login.php` to attempt to login or `https://data.nathcat.net/sso/create-user.php` to create a new user.

### Login Request

To create a login request, send a POST request to `https://data.nathcat.net/sso/try-login.php` with a JSON body containing the following information:
```json
{
    "username": <username>,
    "password": <password>
}
```
The API will then return either a failure message in the format:
```json
{
    "state": "fail",
    "message": <error-message>
}
```
Or a success message in the format:
```json
{
    "state": "success",
    "user": {
        "id": <id>,
        "username": <username>,
        "email": <email>,
        "password": <hashed-password>,
        "fullName": <fullName>
    }
}
```

### Create New User Request

To create a new user request, send a POST request to `https://data.nathcat.net/sso/create-user.php` with a JSON body containing the following information:
```json
{
    "username": <username>,
    "email": <email>,
    "password": <password>,
    "password2": <password2>,
    "fullName": <fullName>
}
```
The API will then return either a failure message in the format:
```json
{
    "state": "fail",
    "message": <error-message>
}
```
Or a success message in the format:
```json
{
    "state": "success"
}
```