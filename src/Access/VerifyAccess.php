<?php

namespace Styde\Html\Access;

trait VerifyAccess
{
    /**
     * The access handler implementation used to check if the user has access
     * or not to a field or menu item.
     *
     * @var \Styde\Html\Access\AccessHandler
     */
    protected $accessHandler;

    /**
     * Set the AccessHandler implementation
     *
     * @param AccessHandler $accessHandler
     */
    public function setAccessHandler(AccessHandler $accessHandler)
    {
        $this->accessHandler = $accessHandler;
    }

    /**
     * Returns true if the $accessHandler is not set, otherwise it relies on the
     * handler implementation to check if the user should has access or not.
     *
     * @param array $options
     * @return bool
     */
    public function checkAccess(array $options)
    {
        return $this->accessHandler==null || $this->accessHandler->check($options);
    }

}