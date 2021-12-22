<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../Models/LocalUser.php';
require_once __DIR__ . '/Traits/SQLGateway.php';

use norb_api\Models\User;

/**
 * Class UserGateway
 * This Provides a Gateway to the Database Items of a User, mainly its id and maybe member since or additional values which might come in the furture
  */
class UserGateway
{
    use SQLGateway;

    public function __construct()
    {
        $this->init_sql();
    }

    private function result_to_User(\mysqli_result $result): User
    {
        $userData = $result->fetch_assoc();
        $User = new User();
        $User->setUsrId((int) $userData['id']);
        $User->setMemberSince($userData['member_since']);
        return $User;
    }

    public function findUser($usr_id): User
    {
        $query = <<<'SQL'
            SELECT users_id.id as id, users_id.member_since as member_since 
            FROM users_id 
            WHERE users_id.id=? LIMIT 1
        SQL;
        $stmt = $this->sql_db->prepare($query);
        $stmt->bind_param('i',$usr_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows != 1)
        {
            throw new  \Exception("User Could not be found");
        }
        $res = $this->result_to_User($result);
        return $res;
    }


}

