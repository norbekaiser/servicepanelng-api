# Endpoint: {api_url}/me/
This Endpoint is Responsible for returning current User Data

# HTTP Requests
The Following HTTP Requests are Possible
___
## GET

### Parameters

#### Required HTTP Headers

```http request
Authorization: session_id
```

#### JSON Payload
* none

### Example

```http request
Authorization: 1234567890
```

### On Success
It will return the Userdata, and which type the user data
Further Requests to {API_URL}/me/type will give more details

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "member_since": "2020-02-02 02:02:02",
    "type": "local"
}
```

### On Failure
 
```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```
