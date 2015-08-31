<?php

namespace spec\Styde\Html\Access;

use Illuminate\Contracts\Auth\Access\Gate;
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

    function it_uses_the_authorization_gate(Gate $gate)
    {
        $gate->check('update-post', [])->shouldBeCalledTimes(3)->willReturn(true);

        $this->setGate($gate);

        $this->check(['allows' => 'update-post'])->shouldReturn(true);
        $this->check(['check' => ['update-post']])->shouldReturn(true);
        $this->check(['denies' => 'update-post'])->shouldReturn(false);
    }

    function it_accepts_gate_arguments(Gate $gate)
    {
        $gate->check('update-post', [1])->shouldBeCalled()->willReturn(true);
        $this->setGate($gate);

        $this->check(['allows' => ['update-post', 1]])->shouldReturn(true);
    }
}

interface UserWithRole {

    public function getRole();

}