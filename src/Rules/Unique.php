<?php

namespace Styde\Html\Rules;

use Styde\Html\FallbackToParent;

class Unique extends \Illuminate\Validation\Rules\Unique
{
    use FallbackToParent;

    /**
     * Create a new rule instance.
     *
     * @param  string $table
     * @param  string $column
     * @param $parent
     */
    public function __construct($table, $column = 'NULL', $parent)
    {
        parent::__construct($table, $column);

        $this->setParent($parent);
    }
}
