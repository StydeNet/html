<?php

namespace spec\Styde\Html\Access;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BasicAccessHandlerSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(true, 'admin');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\Access\BasicAccessHandler');
    }

    function it_checks_for_logged_users()
    {
        $this->check(['logged' => true])->shouldReturn(true);
        $this->check(['logged' => false])->shouldReturn(false);
    }

    function it_checks_for_roles()
    {
        $this->check(['roles' => 'admin|editor'])->shouldReturn(true);
        $this->check(['roles' => ['admin', 'editor']])->shouldReturn(true);
        $this->check(['roles' => ['superadmin']])->shouldReturn(false);
    }

    function it_allows_custom_callback_checks()
    {
        $callback = function () {
            return false;
        };

        $this->check(compact('callback'))->shouldReturn(false);
    }
}
