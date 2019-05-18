<?php

namespace Styde\Html\Tests;

use Mockery as m;
use Styde\Html\Theme;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class ThemeTest extends TestCase
{
    /** @test */
    function it_renders_custom_templates()
    {
        $factory = m::mock(Factory::class, function ($factory) {
            $factory->shouldReceive('make')
                ->with('custom.template', ['data' => 123])
                ->andReturn($this->aViewThatRenders('<html>'));
        });

        $theme = new Theme($factory, 'bootstrap');

        $this->assertSame('<html>', $theme->render('custom.template', ['data' => 123], 'template'));
    }

    /** @test */
    function it_renders_published_templates()
    {
        $factory = m::mock(Factory::class, function ($factory) {
            $factory->shouldReceive('exists')
                ->with('themes/bootstrap/template')
                ->andReturn(true);

            $factory->shouldReceive('make')
                ->with('themes/bootstrap/template', ['data' => 234])
                ->andReturn($this->aViewThatRenders('<html>'));
        });

        $theme = new Theme($factory, 'bootstrap');

        $this->assertSame('<html>', $theme->render(null, ['data' => 234], 'template'));
    }

    /** @test */
    function it_renders_default_templates()
    {
        $factory = m::mock(Factory::class, function ($factory) {
            $factory->shouldReceive('exists')
                ->with('themes/bootstrap/template')
                ->andReturn(false);

            $factory->shouldReceive('make')
                ->with('styde.html::bootstrap/template', ['data' => 234])
                ->andReturn($this->aViewThatRenders('<html>'));
        });

        $theme = new Theme($factory, 'bootstrap');

        $this->assertSame('<html>', $theme->render(null, ['data' => 234], 'template'));
    }

    function it_can_retrieve_the_view_object()
    {
        $factory = m::mock(Factory::class);

        $theme = new Theme($factory, 'bootstrap');

        $this->assertSame($factory, $theme->getView());
    }

    protected function aViewThatRenders($html)
    {
        return tap(m::mock(View::class), function ($view) use ($html) {
            $view->shouldReceive('render')->andReturn($html);
        });
    }
}
