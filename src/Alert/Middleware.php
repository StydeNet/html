<?php

namespace Styde\Html\Alert;

class Middleware
{

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
     * Call the push method of the Alert Container object, which will get the
     * messages in array format (raw), and persist them using the Alert Handler
     * implementation.
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        $response = $next($request);

        $this->alert->push();

        return $response;
    }
}
