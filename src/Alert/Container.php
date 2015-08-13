<?php

namespace Styde\Html\Alert;

use Styde\Html\Theme;
use Illuminate\Translation\Translator as Lang;

class Container
{
    /**
     * The Handler is used to persist, clean and retrieve the alert messages.
     *
     * @var \Styde\Html\Alert\Handler
     */
    protected $handler;
    /**
     * The Theme renders the alert messages using the current theme's templates.
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;
    /**
     * Optional object for translating the messages for the current locale
     * configuration.
     *
     * @var \Illuminate\Translation\Translator
     */
    protected $lang;
    /**
     * Stores the current Alert messages collection.
     *
     * @var array
     */
    protected $messages = array();

    /**
     * Creates a new Alert Container object
     *
     * @param Handler $handler
     * @param Theme $theme
     */
    public function __construct(Handler $handler, Theme $theme)
    {
        $this->handler = $handler;
        $this->theme = $theme;
    }

    /**
     * Set the translator component (this is optional)
     *
     * @param \Illuminate\Translation\Translator $lang
     */
    public function setLang(Lang $lang)
    {
        $this->lang = $lang;
    }

    /**
     * Calls the message() method, using the magic method's name as the message
     * $type. Example:
     *
     * Alert::success('msg') is the same as Alert::message('msg', 'success')
     *
     * @param $method
     * @param $args
     * @return \Styde\Html\Alert\Message
     */
    public function __call($method, $args)
    {
        return $this->message(
            isset($args[0]) ? $args[0] : '',
            $method, // type of the message: success, info, danger, etc.
            isset($args[1]) ? $args[1] : []
        );
    }

    /**
     * Creates and returns a new Alert Message
     *
     * @param string $message
     * @param string $type
     * @param array $args
     * @return \Styde\Html\Alert\Message
     */
    public function message($message = '', $type = 'success', array $args = [])
    {
        return new Message($this, $message, $type, $args);
    }

    /**
     * Adds a new message to the container's collection
     *
     * @param $message
     * @return Message
     */
    public function add(Message $message)
    {
        array_push($this->messages, $message);
        return $message;
    }

    /**
     * Attempts to translate texts if the translator component is set and the
     * lang key is found, otherwise returns the original text.
     *
     * @param $text
     * @param array $parameters
     * @return string
     */
    public function translate($text, $parameters = array())
    {
        if (!is_null($this->lang)) {
            return $this->lang->get($text, $parameters);
        }

        return $text;
    }

    /**
     * Renders a view
     *
     * @param $template
     * @param array $data
     * @return string
     */
    public function view($template, $data = array())
    {
        return $this->theme->render($template, $data);
    }

    /**
     * Retrieves all the alert messages in a raw (array) format for this and the
     * previous requests.
     *
     * @return array
     */
    public function toArray()
    {
        $messages = [];
        foreach ($this->messages as $message) {
            $messages[] = $message->raw();
        }

        $previousMessages = $this->handler->getPreviousMessages();

        if (is_array($previousMessages)) {
            return array_merge($previousMessages, $messages);
        }

        return $messages;
    }

    /**
     * Clear the messages of this and the previous requests
     */
    public function clearMessages()
    {
        $this->handler->clean();
        $this->messages = array();
    }

    /**
     * Persist all the messages through the handler
     */
    public function push()
    {
        $this->handler->push($this->toArray());
    }

    /**
     * Clear and render the alert messages of this and the previous requests.
     *
     * @param string|null $custom
     * @return string
     */
    public function render($custom = null)
    {
        $messages = $this->toArray();

        if ( ! empty ($messages)) {
            $this->clearMessages();
            return $this->theme->render(
                $custom,
                ['messages' => $this->withDefaults($messages)],
                'alert'
            );
        }

        return '';
    }

    /**
     * Add the optional defaults for each alert message so you don't have to use
     * isset in the template nor persist empty values through the handler.
     *
     * @param $messages
     * @return array
     */
    protected function withDefaults($messages)
    {
        $defaults = array(
            'details' => '',
            'html' => '',
            'list' => [],
            'buttons' => [],
        );

        foreach ($messages as &$message) {
            $message = array_merge($defaults, $message);
        }

        return $messages;
    }

}
