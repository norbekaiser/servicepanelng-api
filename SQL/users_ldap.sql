CREATE TABLE IF NOT EXISTS users_ldap (
    dn VARCHAR(120),
    usr_id INT NOT NULL,
    PRIMARY KEY(dn),
    FOREIGN KEY (usr_id) REFERENCES users_id(id)
)