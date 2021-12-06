<?php

namespace norb_api\Models;

require_once __DIR__ .'/User.php';

class TypedUser extends User
{

    private $type;

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function jsonSerialize()
    {
        return array_merge(
            parent::jsonSerialize(),
            array(
                "type" => $this->type
            )
        );
    }
}