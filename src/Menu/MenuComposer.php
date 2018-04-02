<?php

namespace Styde\Html\Menu;

use Styde\Html\Facades\Menu;
use Illuminate\Contracts\Support\Htmlable;

abstract class MenuComposer implements Htmlable
{
    protected $template = null;

    public function toHtml()
    {
        $menu = Menu::make(function (MenuBuilder $items) {
            $this->compose($items);
        });

        $menu->template($this->template);

        return $menu->toHtml();
    }

    abstract public function compose(MenuBuilder $items);
}
