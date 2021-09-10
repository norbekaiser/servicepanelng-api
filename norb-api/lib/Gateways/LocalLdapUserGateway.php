<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/Traits/SQLGateway.php';
require_once __DIR__ . '/Traits/LDAPGateway.php';
require_once __DIR__ . '/../Models/LDAPUser.php';

use norb_api\Models\LDAPUser;

/**
 * Class LocalLdapUserGateway
 * This Gateway Provides a Connection to the locally stored data of the ldap user, e.g. matching of id and ldap_dn
 */
class LocalLdapUserGateway
{
    use SQLGateway;

    /**
     * The Gateway requires a sql and an ldap connection
     * LdapUserGateway constructor.
     */
    public function __construct()
    {
        $this->init_sql();
    }

    private function result_to_LDAPUser(\mysqli_result $result) :LDAPUser
    {
        $userData = $result->fetch_assoc();
        $LDAPUser = new LDAPUser();
        $LDAPUser->setUsrId((int) $userData['usr_id']);
        $LDAPUser->setMemberSince($userData['member_since']);
        $LDAPUser->setDN($userData['dn']);
        return $LDAPUser;
    }

    public function findUserID(int $usr_id): LDAPUser
    {
        $query = <<<'SQL'
            SELECT users_id.id as usr_id,
                   users_id.member_since as member_since,
                   users_ldap.dn as dn
            FROM users_ldap 
            INNER JOIN users_id ON users_ldap.usr_id = users_id.id 
            WHERE usr_id=? LIMIT 1
        SQL;
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('i',$usr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 1)
        {
            throw new \Exception("LDAPUser Could not be found");
        }
        $res =  $this->result_to_LDAPUser($result);
        return $res;
    }

    /**
     * Finds a Ldap User in the Local Table, he will be there if he has once used the service
     * @param string $dn
     * @return LDAPUser
     * @throws \Exception
     */
    public function findUserDN(string $dn): LDAPUser
    {
        $query = <<<'SQL'
            SELECT users_id.id as usr_id,
                   users_id.member_since as member_since,
                   users_ldap.dn as dn
            FROM users_ldap 
            INNER JOIN users_id ON users_ldap.usr_id = users_id.id 
            WHERE dn=? LIMIT 1
        SQL;
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('s',$dn);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 1)
        {
            throw new \Exception("LDAPUser Could not be found");
        }
        $res =  $this->result_to_LDAPUser($result);
        return $res;
    }

    /**
     * Inserts a DN into the local table
     * @param string $dn
     * @return LDAPUser
     * @throws \Exception
     */
    public function InsertUserDN(string $dn): LDAPUser
    {
        $query_insert_usr_id = <<<'SQL'
        INSERT INTO users_id(id,member_since) VALUES (NULL,CURRENT_TIMESTAMP);
        SQL;
        $query_insert_local_ldapuser = <<<'SQL'
            INSERT INTO users_ldap(dn, usr_id) VALUES (?,?);
        SQL;

        $LDAPUser = new LDAPUser();
        $LDAPUser->setDN($dn);

        $this->sql_db->begin_transaction();

        $stmt_insert_usr_id = $this->sql_db->prepare($query_insert_usr_id);
        $stmt_insert_local_userldap = $this->sql_db->prepare($query_insert_local_ldapuser);

        $stmt_insert_usr_id->execute();
        $new_user_id = $stmt_insert_usr_id->insert_id;

        $stmt_insert_local_userldap->bind_param('si',$LDAPUser->getDN(),$new_user_id);
        $stmt_insert_local_userldap->execute();

        if($stmt_insert_local_userldap->affected_rows !=1)
        {
            $this->sql_db->rollback();
            throw new \Exception("LocalLdap User Could not be Added");
        }
        else{
            $this->sql_db->commit();
            $LDAPUser->setUsrId($new_user_id);
        }

        return $LDAPUser;
    }
}
