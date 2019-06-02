<?php

namespace Styde\Html;

interface Transformer
{
    public function fromRequest($value);

    public function forDisplay($value);
}
