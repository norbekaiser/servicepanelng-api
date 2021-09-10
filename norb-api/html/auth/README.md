# Endpoint: {api_url}/auth/
This Endpoint is Responsible for Authenticating a User
It Currently only Supports Authenticating against the Internal User Database

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
| g_recaptcha_response | String | The Recaptcha Response From the Client |

### Example

```json
{
"username": "dave",
"password": "guest",
"g_recaptcha_response": "1234567890"
}
```

### On Success 

```http request
'HTTP/1.1 200 OK'
```
```json
{
  "sessionid": "sessionidkey"
}
```

### On Failure

#### Faulty Input Format

```http request
'HTTP/1.1 400 Bad Request'
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
    "error": "Invalid Credentials"
}
```
