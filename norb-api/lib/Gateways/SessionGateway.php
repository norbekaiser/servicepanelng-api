<?php

namespace norb_api\Gateways;

require_once __DIR__ . '/../Models/Session.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/Traits/RedisGateway.php';

use norb_api\Models\Session;
use norb_api\Models\User;

class SessionGateway
{
    use RedisGateway;
    private $random_bytes_length;
    private $hash_algo;

    public function __construct()
    {
        $this->init_redis();
        $this->random_bytes_length=64;
//        $this->prefix ="session";

    }

    public function find_session(string $sessionToken): Session
    {
        $res = $this->redis_db->hGetAll($sessionToken);
        if(!$res)
        {
            throw new \Exception("Session not Found");
        }
        $session = new Session();
        $session->setUsrId($res['usr_id']);
        $session->setSessionId($res['session_id']);
        return $session;
    }

    public function create_session(User $usr): Session
    {
        $session = new Session();
        $session->setSessionId(base64_encode(random_bytes($this->random_bytes_length)));
        $session->setUsrId($usr->getUsrId());
        //TODO find a better way to get a unique session, a non colliding session

        $this->redis_db->hMSet($session->getSessionId(),array(
            'session_id' => $session->getSessionId(),
            'usr_id' =>  $session->getUsrId(),
        ));

        $this->prolong_session($session);

        return $session;
    }

    public function clear_all_sessions(): void
    {
        $this->redis_db->flushDB();
    }

    public function prolong_session(Session $session): void
    {
        $this->redis_db->expire($session->getSessionId(),1800);
    }

    public function expire_session(Session $session): void
    {
        $this->redis_db->expire($session->getSessionId(),0);
    }
}