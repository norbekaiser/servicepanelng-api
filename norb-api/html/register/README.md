# Endpoint: {api_url}/register/
This Endpoint is Responsible for Registering a User
It Only Registers the User to the Internal Database

# HTTP Requests
The Following HTTP Requests are Possible
___
## POST

### Parameters

#### Required HTTP Headers
 * none

#### JSON Payload
| Parameter | Type | Description |
| :--- |:--- | :--- |
| username | String | The Required Username |
| password | String | The Required Password |
| g_recaptcha_response | String | The Recaptcha Response From the User Client |

### Example

```json
{
    "username": "user",
    "password": "password",
    "g_recaptcha_response": "1234567890"
}
```

### On Success 

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "username": "username"
}
```

### On Failure

#### Faulty Input Format 

```http request
'HTTP/1.1 400 Bad Request'
```
```json
{
    "error": "Registration is Disabled"
}
```
```json
{
    "error": "No Username Supplied"
}
```
```json
{
    "error": "No Password Supplied"
}
```
```json
{
    "error": "No Google Recaptcha Response Supplied"
}
```
```json
{
    "error": "Username is not a Valid Email Address"
}
```
```json
{
    "error": "Password must Contain at least N Characters"
}
```
```json
{
    "error": "Password must Contain at least 1 Letter"
}
```
```json
{
    "error": "Password must Contain at least 1 Digit"
}
```

#### Faulty Input Data

```http request
'HTTP/1.1 422 Unprocessable Entity'
```
```json
{
    "error": "Recaptcha Failed"
}
```
```json
{
    "error": "User Could not be Created"
}
```
