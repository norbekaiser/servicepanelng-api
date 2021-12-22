# Endpoint: {api_url}/me/local/
This Endpoint is Responsible for returning Additional User Data from the Local Storage

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

#### Example

```http request
Authorization: 1234567890
```

### Results

#### Successful Request
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

#### Faulty Authorization
If the User does not have a Valid Session or if he is not a Local User, the Request is unauthorized
 
```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```
___

## PATCH
The Patch requests allows to change a few details, (currently: Password only)
### Parameters

#### Required HTTP Headers

```http request
Authorization: session_id
```

#### JSON Payload

| Parameter | Type | Description |
| :--- |:--- | :--- |
| password | (Optional) String | The Desired new Password |

#### Example

```http request
Authorization: 1234567890
```

### Results

#### Successful Request
It will return if the value was successfully changed

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "password": "modified"
}
```

#### Faulty Authorization
If the User does not have a Valid Session or if he is not a Local User, the Request is unauthorized
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
