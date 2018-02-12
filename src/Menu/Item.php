<?php

namespace Styde\Html\Menu;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Styde\Html\Facades\Html;

abstract class Item
{
    public $text;
    public $secure;
    public $id;
    public $class = '';
    public $active = false;
    public $submenu;
    public $items = [];
    public $included = true;

    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public abstract function url();

    public function classes($classes)
    {
        $this->class = Html::classes((array) $classes, false);

        return $this;
    }

    public function active(bool $value = true)
    {
        $this->active = $value;

        return $this;
    }

    public function submenu(Closure $setup)
    {
        $this->submenu = $setup;

        return $this;
    }

    public function include(bool $value = true)
    {
        $this->included = $value;

        return $this;
    }

    public function ifAuth()
    {
        return $this->include(Auth::check());
    }

    public function ifGuest()
    {
        return $this->include(Auth::guest());
    }

    public function ifCan($ability, $arguments = [])
    {
        return $this->include(Gate::allows($ability, $arguments));
    }

    public function ifCannot($ability, $arguments = [])
    {
        return $this->include(Gate::denies($ability, $arguments));
    }

    public function ifIs($role)
    {
        $user = Auth::user();

        return $this->include($user && $user->isA($role));
    }
}
