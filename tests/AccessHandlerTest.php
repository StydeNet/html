<?php

namespace Styde\Html\Tests;

use Styde\Html\Access\{AccessHandler, BasicAccessHandler};

class AccessHandlerTest extends TestCase
{
    /** @test */
    function it_instantiates_a_basic_access_handler_object()
    {
        $accessHandler = $this->app->make(AccessHandler::class);

        $this->assertInstanceOf(BasicAccessHandler::class, $accessHandler);
    }
}