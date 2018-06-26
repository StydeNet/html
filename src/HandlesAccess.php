<?php

namespace Styde\Html;

use Illuminate\Support\Facades\{Auth, Gate};

trait HandlesAccess
{
    /**
     * With this property we verify if it is included or not.
     *
     * @var bool
     */
    public $included = true;

    /**
     * It is checked that the file is included and if it is not, all the rules are deactivated
     *
     * @param bool $value
     * @return $this
     */
    public function includeIf(bool $value = true)
    {
        $this->included = $value;

        if (! $this->included && isset($this->rules)) {
            $this->disableRules();
        }

        return $this;
    }

    /**
     * Include only if the user is authenticated
     *
     * @return HandlesAccess
     */
    public function ifAuth()
    {
        return $this->includeIf(Auth::check());
    }

    /**
     * Include only if the user is guest
     *
     * @return HandlesAccess
     */
    public function ifGuest()
    {
        return $this->includeIf(Auth::guest());
    }

    /**
     * Include only if the user has the required ability
     *
     * @param string $ability
     * @param array $arguments
     * @return HandlesAccess
     */
    public function ifCan($ability, $arguments = [])
    {
        return $this->includeIf(Gate::allows($ability, $arguments));
    }

    /**
     * Include only if the user does not have the required ability
     *
     * @param string $ability
     * @param array $arguments
     * @return HandlesAccess
     */
    public function ifCannot($ability, $arguments = [])
    {
        return $this->includeIf(Gate::denies($ability, $arguments));
    }

    /**
     * Include only if the user has the specified role
     *
     * @param string $role
     * @return HandlesAccess
     */
    public function ifIs($role)
    {
        $user = Auth::user();

        return $this->includeIf($user && $user->isA($role));
    }
}
