# Endpoint: {api_url}/ldap/PosixAccount/
This Endpoint is Responsible for Giving Additional User Data from LDAP, specifically Posix Account Data

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
It will return the PosixAccount Data, optional values will be omitted 
 * dn
 * cn
 * uid
 * uidNumber
 * gidNumber
 * homeDirectory
 * loginShell

```http request
'HTTP/1.1 200 OK'
```
```json
{
    "username": "cn=dave,ou=users,dc=ldap,dc=example,dc=com",
    "cn": "dave",
    "uid": "dave",
    "uidNumber": 1234,
    "gidNumber": 1234,
    "homeDirectory": "/home/dave",
    "loginShell": "/bin/bash"
}
```

### Faulty Authorization
```http request
'HTTP/1.1 401 Unauthorized'
```
```json
```

### Local User Requests LDAP Data
Will be returned if despite not beeing an ldap user
```http request
'HTTP/1.1 403 Forbidden'
```
```json
```

### False LDAP Object Class
Will be returned if a valid ldap user despite not beeing in the class requests data for this objectclass
```http request
'HTTP/1.1 404 Unauthorized'
```
```json
```
