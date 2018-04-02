<?php

namespace Styde\Html\Menu;

use Styde\Html\Facades\Menu;
use Illuminate\Contracts\Support\Htmlable;

abstract class MenuComposer implements Htmlable
{
    protected $template = null;

    public function render()
    {
        $menu = Menu::make(function (MenuBuilder $items) {
            $this->compose($items);
        });

        return $menu->render($this->template);
    }

    abstract public function compose(MenuBuilder $items);

    public function toHtml()
    {
        return $this->render();
    }
}