<?php

namespace Styde\Html\Alert;

use Illuminate\Session\Store as Session;

class SessionHandler implements Handler
{
    /**
     * Laravel's component to handle sessions
     *
     * @var \Illuminate\Session\Store
     */
    protected $session;
    /**
     * Reserved session key for this component
     *
     * @var string $key
     */
    protected $key;

    /**
     * Creates a new SessionHandler instance.
     *
     * This class will allow us to persist the alert messages to the next
     * request using the Laravel's Session component.
     *
     * @param Session $session
     * @param $key
     */
    public function __construct(Session $session, $key)
    {
        $this->session = $session;
        $this->key = $key;
    }

    /**
     * Get the messages from the previous request(s)
     *
     * @return array
     */
    public function getPreviousMessages()
    {
        return $this->session->get($this->key, []);
    }

    /**
     * Save the messages to the session
     * This will be typically called through the Middleware's terminate method.
     *
     * @param array $messages
     */
    public function push(array $messages)
    {
        $this->session->put($this->key, $messages);
    }

    /**
     * Clear all the messages from the session.
     * Useful once the messages has been rendered.
     */
    public function clean()
    {
        $this->session->put($this->key, null);
    }

}
