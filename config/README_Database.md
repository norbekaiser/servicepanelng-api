# Configuration Database
To Configure the Database Connection modify the ini file as followiong

## database.ini
Copy database.ini.default to database.ini
```bash
cp database.ini.default database.ini
```

## Fileformat
Default Values
```ini
[database]
Hostname = localhost
Port = 3306
Name = database
Username = user
Password = password
Socket = /var/run/mysqld/mysqld.sock
```

### Hostname
The Hostname to which the SQL Server should connect, notice that localhost implies a unix socket connection

### Port
The Databaase port to be used, if localhost is specified it will be ommited by mysqli, use 127.0.0.1 instead

### Name
The Database Name to use

### Username
The Username which is used to connect

### Password
The Password which is used to connect

### Socket
The Socketlocation, if non specified
