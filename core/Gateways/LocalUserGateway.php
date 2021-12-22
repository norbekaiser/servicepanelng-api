<?php
//    Copyright (c) 2021 Norbert Rühl
//    
//    This software is provided 'as-is', without any express or implied warranty. In no event will the authors be held liable for any damages arising from the use of this software.
//    
//    Permission is granted to anyone to use this software for any purpose, including commercial applications, and to alter it and redistribute it freely, subject to the following restrictions:
//    
//        1. The origin of this software must not be misrepresented; you must not claim that you wrote the original software. If you use this software in a product, an acknowledgment in the product documentation would be appreciated but is not required.
//    
//        2. Altered source versions must be plainly marked as such, and must not be misrepresented as being the original software.
//    
//        3. This notice may not be removed or altered from any source distribution.
?>
<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../Models/LocalUser.php';
require_once __DIR__ . '/Traits/SQLGateway.php';

use norb_api\Models\LocalUser;

/**
 * Class LocalUserGateway
 * This Provides a Gateway to the Database Items of a Local user, consisting of a username
 * This Gateway also provides the Authentication Feature of a Local User, as it is against the Database
 */
class LocalUserGateway
{
    use SQLGateway;


    public function __construct()
    {
        $this->init_sql();

    }

    private function result_to_LocalUser(\mysqli_result $result) :LocalUser
    {
        $userData = $result->fetch_assoc();
        $LocalUser = new LocalUser();
        $LocalUser->setUsrId((int) $userData['usr_id']);
        $LocalUser->setUsername($userData['username']);
        $LocalUser->setMemberSince($userData['member_since']);
        return $LocalUser;
    }

    public function findUserByUsrID(int $usr_id): LocalUser
    {
        $query = <<<'SQL'
            SELECT 
                   users_id.id as usr_id, 
                   users_id.member_since as member_since, 
                   users_local.username as username 
            FROM users_local 
            INNER JOIN users_id ON users_local.usr_id = users_id.id 
            WHERE usr_id=? LIMIT 1
        SQL;
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('i',$usr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 1)
        {
            throw new \Exception("LocalUser Could not be found By UsrID");
        }
        $res = $this->result_to_LocalUser($result);
        return $res;
    }


    public function findUserByUsername(string $username): LocalUser
    {
        $query = <<<'SQL'
            SELECT 
                   users_id.id as usr_id, 
                   users_id.member_since as member_since, 
                   users_local.username as username 
            FROM users_local 
            INNER JOIN users_id ON users_local.usr_id = users_id.id 
            WHERE username=? LIMIT 1
        SQL;
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 1)
        {
            throw new \Exception("LocalUser Could not be found By Username");
        }
        $res = $this->result_to_LocalUser($result);
        return $res;
    }

    public function insertLocalUser(string $username,string $password): LocalUser
    {
        $query_insert_usr_id = <<<'SQL'
            INSERT INTO users_id (id,member_since) VALUES (NULL,CURRENT_TIMESTAMP);
        SQL;
        $query_insert_local_user = <<<'SQL'
            INSERT INTO users_local(username, usr_id, password) VALUES (?,?,?);
        SQL;
        $LocalUser = new LocalUser();
        $LocalUser->setUsername($username);

        $this->sql_db->begin_transaction();

        $stmt_insert_usr_id = $this->sql_db->prepare($query_insert_usr_id);
        $stmt_insert_local_user = $this->sql_db->prepare($query_insert_local_user);

        $stmt_insert_usr_id->execute();
        $new_user_id = $stmt_insert_usr_id->insert_id;
        $password_hash = password_hash($password,PASSWORD_DEFAULT);


        $stmt_insert_local_user->bind_param('sis',$username,$new_user_id,$password_hash);
        $stmt_insert_local_user->execute();

        if($stmt_insert_local_user->affected_rows !=1)
        {

            $this->sql_db->rollback();
            throw new \Exception("LocalUser Could not be Created");
        }
        else{
            $this->sql_db->commit();
            $LocalUser->setUsrId($new_user_id);
        }
        return $LocalUser;
    }

    private function PasswordVerify($password,\mysqli_result $result): bool
    {
        $userData = $result->fetch_assoc();
        if(!password_verify($password,$userData['password']))
        {
            throw new \Exception("Invalid Password");
        }
        return true;
    }

    public function Authenticate(string $username, string $password): LocalUser
    {
        $query = <<<'SQL'
            SELECT 
                   users_id.id as usr_id, 
                   users_id.member_since as member_since, 
                   users_local.username as username, 
                   users_local.password as password
            FROM users_local 
            INNER JOIN users_id ON users_local.usr_id = users_id.id 
            WHERE username=? LIMIT 1
        SQL;
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('s',$username);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 1)
        {
            throw new \Exception("LocalUser Could not be found By Username");
        }
        $this->PasswordVerify($password,$result);
        mysqli_data_seek($result,0);
        $res = $this->result_to_LocalUser($result);
        return $res;
    }

    public function ChangePassword(LocalUser $user,string $password): void
    {
        $query = <<<'SQL'
            UPDATE users_local
            INNER JOIN users_id ON users_local.usr_id = users_id.id
            SET 
                users_local.password =?
            WHERE users_local.username=? LIMIT 1
        SQL;
        $password_hash = password_hash($password,PASSWORD_DEFAULT);
        $username = $user->getUsername();
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('ss',$password_hash, $username);
        $stmt->execute();
        if($stmt->affected_rows != 1)
        {
            throw new \Exception("Password not Changed");
        }
    }

}

