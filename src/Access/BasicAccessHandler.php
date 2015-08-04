<?php

namespace Styde\Html\Access;

class BasicAccessHandler implements AccessHandler {

    /**
     * @var bool $loggedIn Whether the user is logged in or not
     */
    protected $loggedIn;
    /**
     * @var string $role User's role in the application
     */
    protected $role;

    /**
     * @param $loggedIn
     * @param $role
     */
    public function __construct($loggedIn, $role)
    {
        $this->loggedIn = $loggedIn;
        $this->role = $role;
    }

    /**
     * Check if the user has access to an item based on the passed $options.
     *
     * The method will search for one of these options, in the following order,
     * and only one option will be used to check if the user has access:
     *
     * 1. callback (should return true if access is granted, false otherwise)
     * 2. logged (true: requires authenticated user, false: requires guest user)
     * 3. roles (true if the user has any of the required roles)
     * 4. Returns true if no security options are set.
     *
     * @param array $options
     * @return bool
     */
    public function check(array $options)
    {
        if (isset($options['callback'])) {
            return call_user_func($options['callback'], $options);
        }

        if (isset($options['logged'])) {
            return $options['logged'] === $this->loggedIn;
        }

        if (isset($options['roles'])) {
            return $this->checkRole($options['roles']);
        }

        return true;
    }

    /**
     * Check if a user has at least one of the allowed roles.
     *
     * @param  $allowed
     * @return bool
     */
    protected function checkRole($allowed)
    {
        if (!is_array($allowed)) {
            $allowed = explode('|', $allowed);
        }

        return in_array($this->role, $allowed);
    }
}
