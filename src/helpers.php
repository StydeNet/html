<?php

/**
 * Convenient helpers in case you prefer to use them instead of the facades
 */

if (!function_exists('alert')) {
    /**
     * Creates a new alert message (alias of Alert::make)
     *
     * @param string $message
     * @param string $type
     * @param array $args
     * @return string
     */
    function alert($message = '', $type = 'success', $args = []) {
        return App::make('alert')->message($message, $type, $args);
    }
}

if(!function_exists('menu')) {
    /**
     * Generates a new menu (alias of Menu::make)
     *
     * @param $items
     * @param string|null $classes
     * @return string
     */
    function menu($items, $classes = null) {
        return App::make('menu')->make($items, $classes);
    }
}