<?php
namespace Styde\Html\Alert;

interface Handler
{
    /**
     * Get the previous messages (i.e. from the previous request)
     *
     * @return array
     */
    public function getPreviousMessages();

    /**
     * Persist the messages (to the session, a cookie, a file or DB etc.)
     *
     * @param array $messages
     */
    public function push(array $messages);

    /**
     * Clear the messages (stored in the session, cookie, file or DB, etc.)
     */
    public function clean();
}