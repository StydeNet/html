<?php

namespace Styde\Html\Tests;

use Illuminate\Support\Facades\View;
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

    /** @test */
    function can_customize_the_template()
    {
        View::addLocation(__DIR__.'/views');

        Alert::message('This is a message', 'info');

        $this->assertTemplateMatches('alert/custom-template', Alert::render('custom-templates.alert'));
    }

    /** @test */
    function it_can_render_view_inside_the_alert()
    {
        View::addLocation(__DIR__.'/views');

        Alert::info()->view('custom-templates.partial-for-alert');

        $this->assertTemplateMatches('alert/with-partial-view', Alert::render());
    }

    /** @test */
    function it_returns_raw_messages()
    {
        Alert::info('This is a info');
        Alert::message('This is a message');

        $this->assertEquals([
            [
                'message' => 'This is a info',
                'type' => 'info'
            ],
            [
                'message' => 'This is a message',
                'type' => 'success'
            ]
        ], Alert::toArray());
    }
}
