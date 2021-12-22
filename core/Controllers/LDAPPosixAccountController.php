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

namespace servicepanel_ng;

require_once __DIR__ . '/../Controllers/AbstractSessionController.php';
require_once __DIR__ . '/../Exceptions/HTTP401_Unauthorized.php';
require_once __DIR__ . '/../Exceptions/HTTP404_NotFound.php';
require_once __DIR__ . '/../Gateways/LocalLdapUserGateway.php';
require_once __DIR__ . '/../Gateways/SessionGateway.php';
require_once __DIR__ . '/../Gateways/LdapPosixAccountGateway.php';

use norb_api\Controllers\AbstractSessionController;
use norb_api\Exceptions\HTTP403_Forbidden;
use norb_api\Exceptions\HTTP404_NotFound;
use norb_api\Gateways\LocalLdapUserGateway;

class LDAPPosixAccountController extends AbstractSessionController
{
    private $LocalLDAPUser = null;
    private $LdapPosixAccountGateway = null;

    public function __construct(string $requestMethod, string $Authorization)
    {
        $this->LdapPosixAccountGateway = new LdapPosixAccountGateway();
        parent::__construct($requestMethod, $Authorization);
    }

    protected function ParseSession()
    {
        try
        {
            $localLdapUserGateway = new LocalLdapUserGateway();
            $this->LocalLDAPUser = $localLdapUserGateway->findUserID($this->Session->getUsrId());
        }
        catch (\Exception $e)
        {
            $this->LocalLDAPUser= null;
        }
    }

    private function require_valid_local_ldap_user()
    {
        if(is_null($this->LocalLDAPUser))
        {
            throw new HTTP403_Forbidden("User must be an LDAP User to perform this request");
        }
    }

    protected function GetRequest()
    {
        $this->require_valid_local_ldap_user();
        try{
            $data = $this->LdapPosixAccountGateway->find($this->LocalLDAPUser);
        }
        catch (\Exception $e){
            throw new HTTP404_NotFound();
        }

        $resp['status_code_header'] = 'HTTP/1.1 200 OK';
        $resp['data'] = $data;
        return $resp;
    }
}
