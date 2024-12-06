<?php

namespace MMXXIV\Day6;

class LoopDetectedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Loop detected.');
    }
}
