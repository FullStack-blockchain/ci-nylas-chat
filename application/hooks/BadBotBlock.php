<?php

defined('BASEPATH') or exit('No direct script access allowed');

class BadBotBlock
{
    public function init()
    {
        if (defined('APP_BAD_BOTS_BLOCK') && APP_BAD_BOTS_BLOCK) {
            \Nabble\SemaltBlocker\Blocker::protect();
        }
    }
}
