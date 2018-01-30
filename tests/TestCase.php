<?php

namespace Styde\Html\Tests;

use Styde\Html\HtmlServiceProvider;
use Styde\Html\{FormBuilder, HtmlBuilder};
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use TestHelpers;

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
        return app(HtmlBuilder::class);
    }

    protected function newFormBuilder()
    {
        return app(FormBuilder::class);
    }

    protected function getPackageProviders($app)
    {
        return [HtmlServiceProvider::class];
    }
}
