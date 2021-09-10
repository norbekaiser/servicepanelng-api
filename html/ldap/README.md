# Endpoint: {api_url}/ldap/
This Endpoint is Responsible for Giving Additional User Data from LDAP, first of all the objectClass

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
    "username": "cn=dave,ou=users,dc=ldap,dc=example,dc=com",
    "posixAccount": true,
    "inetOrgPerson": true
}
```

### On Failure
 
```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```
