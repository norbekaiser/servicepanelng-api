# Endpoint: {api_url}/me/local/
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
It will return the Local Userdata

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "username": "user",
    "member_since": "2020-02-02 02:02:02"
}
```

### On Failure
 
```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```
___

## PATCH

### Parameters

#### Required HTTP Headers

```http request
Authorization: session_id
```

#### JSON Payload

| Parameter | Type | Description |
| :--- |:--- | :--- |
| password | (Optional) String | The Desired new Password |

### Example

```http request
Authorization: 1234567890
```

### On Success
It will return if the value was successfully changed

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "password": "modified"
}
```

### On Failure
 
#### Unauthenticated

```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```

#### Faulty Input Data

```http request
'HTTP/1.1 400 Bad Request'
```
```json
```
