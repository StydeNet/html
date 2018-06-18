<?php

namespace Styde\Html;

use Illuminate\Support\Facades\{Auth, Gate};

trait HandlesAccess
{
    public $included = true;

    public function includeIf(bool $value = true)
    {
        $this->included = $value;

        if (! $this->included && isset($this->rules)) {
            $this->disableRules();
        }

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
        if ($this->rules && ! Gate::allows($ability, $arguments)) {
            $this->rules = [];
        }

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
