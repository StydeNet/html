<?php

namespace Styde\Html\Tests;

use Styde\Html\HtmlServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    use TestHelpers;

    protected function getPackageProviders($app)
    {
        return [HtmlServiceProvider::class];
    }
}
