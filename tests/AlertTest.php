<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Alert;

class AlertTest extends TestCase
{
    /** @test */
    function it_renders_new_messages()
    {
        Alert::message('This is a message', 'info');

        $this->assertTemplateMatches('alert/alert', Alert::render());
    }

    /** @test */
    function it_uses_magic_methods()
    {
        Alert::success('Success!');
        Alert::info('Some information');

        $this->assertTemplateMatches('alert/magic', Alert::render());
    }

    /** @test */
    function it_chains_methods_to_build_complex_alert_messages()
    {
        Alert::info('Your account is about to expire')
            ->details('A lot of knowledge still waits for you:')
            ->items([
                'Laravel courses',
                'OOP classes',
                'Access to real projects',
                'Support',
                'And more'
            ])
            ->button('Renew now!', '#', 'primary')
            ->button('Take me to your leader', 'http://google.com', 'info');

        $this->assertTemplateMatches('alert/complex', Alert::render());
    }
}
