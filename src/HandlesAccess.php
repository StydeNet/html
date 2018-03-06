<?php

namespace Styde\Html;

use Illuminate\Support\Facades\{Auth, Gate};

trait HandlesAccess
{
    public $included = true;

    public function includeIf(bool $value = true)
    {
        $this->included = $value;

        return $this;
    }

    public function ifAuth()
    {
        return $this->includeIf(Auth::check());
    }

    public function ifGuest()
    {
        return $this->includeIf(Auth::guest());
    }

    public function ifCan($ability, $arguments = [])
    {
        return $this->includeIf(Gate::allows($ability, $arguments));
    }

    public function ifCannot($ability, $arguments = [])
    {
        return $this->includeIf(Gate::denies($ability, $arguments));
    }

    public function ifIs($role)
    {
        $user = Auth::user();

        return $this->includeIf($user && $user->isA($role));
    }
}
