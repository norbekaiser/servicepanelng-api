# Endpoint: {api_url}/ldap/InetOrgPeron/
This Endpoint is Responsible for Giving Additional User Data from LDAP, specifically Inet Org Person Data

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
It will return the InetOrgPerson Data, mainly
 * dn
 * cn
 * sn
 * mail
 * givenName(s)

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "username": "cn=dave,ou=users,dc=ldap,dc=example,dc=com",
    "cn": "dave",
    "sn": "Dave",
    "mail": "dave@example.com",
    "givenName": [
      "Dave"
    ]
}
```

### Faulty Authorization
```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```

### False LDAP Object Class
Will be returned if despite not beeing in the class it is requested
```http request
'HTTP/1.1 404 Unauthorized'
```
```json
```
