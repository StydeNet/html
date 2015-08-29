<?php

namespace spec\Styde\Html\Access;

use Illuminate\Contracts\Auth\Guard as Auth;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BasicAccessHandlerSpec extends ObjectBehavior
{
    function let(Auth $auth)
    {
        $this->beConstructedWith($auth);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Styde\Html\Access\BasicAccessHandler');
    }

    function it_checks_for_logged_users(Auth $auth)
    {
        $auth->check()->shouldBeCalledTimes(2)->willReturn(true);

        $this->check(['logged' => true])->shouldReturn(true);
        $this->check(['logged' => false])->shouldReturn(false);
    }

    function it_checks_for_roles(Auth $auth, UserWithRole $user)
    {
        $user->getRole()->shouldBeCalledTimes(3)->willReturn('admin');
        $auth->check()->shouldBeCalledTimes(3)->willReturn(true);
        $auth->user()->willReturn($user);

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

interface UserWithRole {

    public function getRole();

}