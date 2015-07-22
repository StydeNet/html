<?php

namespace Styde\Html\Alert;

class Middleware {

    /**
     * @var Container
     */
    protected $alert;

    /**
     * Creates a new Alert Middleware instance. This class will be used to
     * persist the alert messages once the response has been sent to the user.
     *
     * @param Container $alert
     */
    public function __construct(Container $alert)
    {
        $this->alert = $alert;
    }

    /**
     * This is just a requisite of the framework's middleware.
     * We are not doing anything special here.
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        return $next($request);
    }

    /**
     * Call the push method of the Alert Container object, which will get the
     * messages in array format (raw), and persist them using the Alert Handler
     * implementation.
     *
     * @param $request
     * @param $response
     */
    public function terminate($request, $response)
    {
        $this->alert->push();
    }

}