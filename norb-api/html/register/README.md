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
| g_recaptcha_response | String (Optional unless Configured) | The Google Recaptcha Response From the Client |
| friendlycaptcha_solution | String (Optional unless Configured) | The Friendlycaptcha Solution From the Client |

#### Example

```json
{
    "username": "user",
    "password": "password",
    "g_recaptcha_response": "1234567890"
}
```

### Results

#### Successful Request
If a Valid User was created, it will return the new users Data, e.g. the Username
```http request
'HTTP/1.1 200 OK'
```
```json
{
    "username": "username"
}
```

#### Faulty Input Format
Faulty Input Format can cause Multiple Errors 

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
Despite a valid Payload, the User Creation might fail if the user already exists or e.g. the captcha failed

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
