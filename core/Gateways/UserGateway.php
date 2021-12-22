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

