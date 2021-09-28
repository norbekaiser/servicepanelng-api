# Endpoint: {api_url}/ldap/PosixGroup/
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
It will return an array of PosixGroup Data
 * dn
 * cn
 * gidNumber

```http request
'HTTP/1.1 200 OK'
```
```json
[
  {
    "dn": "cn=mygroup,ou=groups,dc=ldpsrv,dc=example,dc=com",
    "cn": "mygroup",
    "gidNumber": 1501
  },
  {
    "dn": "cn=mygroup2,ou=groups,dc=ldpsrv,dc=example,dc=com",
    "cn": "mygroup2",
    "gidNumber": 1502
  }
]
```

### No Groups Found
```http request
'HTTP/1.1 204 NoContent'
```
```json
```

### Faulty Authorization
```http request
'HTTP/1.1 403 Forbidden'
```
```json
```

### False LDAP Object Class
Will be returned if despite not beeing in the class it is requested, only posixUser may find PosixGroups, however maybe migrate to 403 instead since this can not be fixed with proper authroization
```http request
'HTTP/1.1 403 Forbidden'
```
```json
```