<?php

namespace Styde\Html;

class Str extends \Illuminate\Support\Str
{

    /**
     * Convert camel cases, underscore and hyphen separated strings to human
     * format.
     *
     * @param $string
     * @return string
     */
    public static function title($string)
    {
        $stringr = $string[0].preg_replace('@[_-]|([A-Z])@', ' $1', substr($string, 1));
        return ucfirst(strtolower($stringr));
    }

    /**
     * Convert text links to HTML links.
     *
     * @param $text
     *
     * @return mixed
     */
    public static function linkify($text)
    {
        return preg_replace(
            '@(https?://[a-z0-9_./?=&-]+)@i',
            '<a href="$1" target="_blank">$1</a>',
            $text
        );
    }

    /**
     * Cuts a string but without leaving incomplete words and adding a $end
     * string if necessary.
     *
     * @param $value
     * @param $length
     * @param string $end
     *
     * @return mixed|string
     */
    public static function teaser($value, $length, $end = '...')
    {
        if (empty($value)) {
            return '';
        }

        $value = strip_tags($value);
        $value = preg_replace('/(\s+)/', ' ', $value);
        $long = strlen($value);

        if ($long <= $length) {
            return $value;
        }

        $pos = strrpos($value, ' ', $length - $long);
        $value = substr($value, 0, $pos ?: $length);
        return $value.$end;
    }
}
