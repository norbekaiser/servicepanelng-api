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
| g_recaptcha_response | String (Optional unless Configured) | The Google Recaptcha Response From the Client |
| friendlycaptcha_solution | String (Optional unless Configured) | The Friendlycaptcha Solution From the Client |

#### Example

```json
{
"username": "dave",
"password": "guest",
"g_recaptcha_response": "1234567890"
}
```

### Results

### Successful Request 

```http request
'HTTP/1.1 200 OK'
```
```json
{
  "sessionid": "sessionidkey"
}
```

#### Faulty Input Format
When Authorizing, the Request may fail e.g. due to a Faulty Input Format
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
Despite a Valid Format, the Authorization Request might still fail due to false Captcha and/or Invalid Credentials
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
