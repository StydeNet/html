<?php

namespace Styde\Html\Alert;

class Message
{
    /**
     * Container object, useful to translate texts, render views and stores the
     * messages that will be persisted at the end of the request.
     *
     * @var \Styde\Html\Alert\Container
     */
    protected $container;
    /**
     * The $message array will contain all the alert message information (text,
     * details, list, call to actions, etc.)
     *
     * @var array
     */
    protected $message;

    /**
     * Creates a new Alert Message instance and adds it to the container.
     *
     * Messages will be typically created from the Alert Container message
     * method, via the magic methods, or by using the facade Alert::message()
     * which of course references the Alert Container.
     *
     * @param Container $container
     * @param string $message
     * @param string $type
     * @param array $args
     */
    public function __construct(
        Container $container,
        $message = '',
        $type = 'success',
        array $args = []
    ) {
        $this->container = $container;
        $this->message = [
            'message' => $this->container->translate($message, $args),
            'type' => $type
        ];

        $this->container->add($this);
    }

    /**
     * Returns the message in array format
     *
     * @return array
     */
    public function raw()
    {
        return $this->message;
    }

    // Methods for chaining:

    /**
     * Add details to the alert message
     *
     * @param $details
     * @return \Styde\Html\Alert\Message $this
     */
    public function details($details, $parameters = array())
    {
        $this->message['details'] = $this->container->translate($details, $parameters);
        return $this;
    }

    /**
     * Add a call to action (button) to the alert message
     *
     * @param string $text
     * @param string $url
     * @param string $class
     * @param array $parameters
     * @return \Styde\Html\Alert\Message $this
     */
    public function button($text, $url, $class = 'default', $parameters = array())
    {
        $this->message['buttons'][] = array(
            'text' => $this->container->translate($text, $parameters),
            'url' => $url,
            'class' => $class,
        );
        return $this;
    }

    /**
     * Add additional content in HTML format to the message.
     * Warning: this function won't sanitize HTML so please be careful.
     *
     * @param string $html
     * @return \Styde\Html\Alert\Message $this
     */
    public function html($html)
    {
        $this->message['html'] = $html;
        return $this;
    }

    /**
     * Add an additional view partial to the alert message.
     *
     * @param string $template
     * @param array $data
     * @return \Styde\Html\Alert\Message $this
     */
    public function view($template, $data = array())
    {
        return $this->html($this->container->view($template, $data));
    }

    /**
     * Pass a list of items (usually errors) to the alert message
     *
     * @param $items
     * @return \Styde\Html\Alert\Message $this
     */
    public function items($items)
    {
        $items = !is_array($items) ? $items->all() : $items;
        $this->message['items'] = $items;
        return $this;
    }
}
