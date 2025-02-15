<?php

namespace Styde\Html\Transformer;

use BackedEnum;
use Styde\Html\Transformer;

class EnumTransformer implements Transformer
{
    /**
     * @param string<enum-class> $enumClass
     */
    public function __construct(private string $enumClass)
    {
    }

    public function fromRequest($value)
    {
        $enumClass = $this->enumClass;

        return $enumClass::from($value);
    }

    public function forDisplay($value)
    {
        if ($value instanceof BackedEnum) {
            return $value->value;
        }
    }
}
