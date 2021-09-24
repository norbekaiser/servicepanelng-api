<?php

namespace norb_api\Models;

class User
{
    protected $usr_id;
    protected $member_since;

    public function getUsrId(): int
    {
        return $this->usr_id;
    }

    public function setUsrId(int $usr_id): void
    {
        $this->usr_id = $usr_id;
    }

    public function getMemberSince()
    {
        return $this->member_since;
    }

    public function setMemberSince($member_since): void
    {
        $this->member_since = $member_since;
    }



}