<?php

namespace Styde\Html\Tests;

use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Mockery;
use Styde\Html\FormBuilder;
use Styde\Html\HtmlBuilder;
use Styde\Html\Theme;

class TestCase extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $viewFactory;

    /**
     * @var \Mockery\MockInterface
     */
    protected $urlGenerator;

    protected function newHtmlBuilder()
    {
        return new HtmlBuilder($this->mockUrlGenerator(), $this->mockViewFactory());
    }

    protected function newFormBuilder()
    {
        return new FormBuilder($this->mockUrlGenerator(), $this->newTheme(), 'csrf_token_value');
    }

    protected function newTheme()
    {
        return new Theme($this->mockViewFactory(), 'bootstrap');
    }

    protected function mockViewFactory()
    {
        return $this->viewFactory = Mockery::mock(Factory::class);
    }

    protected function mockUrlGenerator()
    {
        return $this->urlGenerator = Mockery::mock(UrlGenerator::class);
    }

    protected function mockView()
    {
        return Mockery::mock(View::class);
    }

    protected function tearDown()
    {
        Mockery::close();
    }
}