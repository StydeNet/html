<?php

namespace Styde\Html;

use Illuminate\Support\Str;

class String extends Str
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
     * Convert a string (title or name) to a SLUG to be used as part of an URL.
     *
     * @param $string
     * @return string
     */
    public static function slugify($string)
    {
        // transliterate
        if (function_exists('iconv')) {
            $string = iconv('utf-8', 'ascii//TRANSLIT', $string);
        }

        // lowercase
        $string = strtolower($string);

        $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
        $string = preg_replace('@\-+@', '-', $string);
        $string = trim($string, '-');

        return $string;
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
