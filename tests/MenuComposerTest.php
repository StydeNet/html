<?php

namespace Styde\Html\Tests;

class MenuClassTest
{
    /** @test */
    function it_builds_a_menu()
    {
        $this->assertTrue(true); // @TODO: create menu composer
    }
}


class MyMenuComposer
{
    public function setup($items)
    {
        $items->url('/', 'Home');
        $items->placeholder('About');
        $items->url('projects', 'Our projects');
        $items->url('contact-us', 'Contact');
    }
}