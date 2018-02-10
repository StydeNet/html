<?php

namespace Styde\Html\Access;

class AuthAccess
{
    public function allow()
    {
        return auth();
    }
}