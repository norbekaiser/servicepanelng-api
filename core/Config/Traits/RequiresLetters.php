<?php

namespace norb_api\Config;

trait RequiresLetters
{
    private $RequiresLetters;

    public function getRequiresLetters(): bool
    {
        return $this->RequiresLetters;
    }
}
