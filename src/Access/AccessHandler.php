<?php

namespace Styde\Html\Access;

interface AccessHandler
{

    /**
     * Performs a series of checks according to the $options parameter and the
     * data assigned through the different implementations. The method should
     * return true if the user has access and false otherwise.
     *
     * @param  array $options
     * @return bool
     */
    public function check(array $options);

}