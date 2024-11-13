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

### User search request

AuthCat also provides a way to search for users. To use this feature, send a POST request to `https://data.nathcat.net/sso/user-search.php`,
providing the following data in a JSON body:

#### Search by ID
```json
{
    "id": Integer
}
```
Note that if `id` is specified in the request body, then _all other provided data in the body will be ignored_, and AuthCat will only search
for a user by the provided `id`.

#### Search by username
```json
{
    "username": String
}
```
AuthCat appends a wildcard character to the end of the provided username, so will return any user which has a username starting with the provided
username.

#### Search by full name
```json
{
    "fullName": String
}
```
AuthCat appends a wildcard character to the end of the provided name, so will return any user which has a name starting with the provided
name.

#### Combining search fields
If the `id` field is present in the body, then AuthCat will ignore any other provided data and only search for a user with the provided `id`.

You may however, specify _both_ the `username` and `fullName` fields, and AuthCat will perform both searches separately and return the results
for both.

### User search response
If successful, AuthCat will respond with JSON in the following format:
```json
{
    "state": "success",
    "results": {
        // "results" contains all returned users, with their ID as the key to their data, like the following:
        <ID>: {
            "id": Integer,
            "username": String,
            "fullName": String,
            "pfpPath": String
        }
    }
}
```

If searching by `id`, and AuthCat cannot find any user with the given `id`, it will respond with:
```json
{
    "state": "fail", 
    "message": "User not found"
}
```

In other search methods, if no user is found, the `results` field is simply empty.

Other errors will be reported under the response format:
```json
{
    "state": "fail",
    "message": String
}
```