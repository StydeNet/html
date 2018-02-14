<?php

namespace Styde\Html\Tests;

use Styde\Html\FormModel\ButtonCollection;

class ButtonCollectionTest extends TestCase
{
    /** @test */
    function it_renders_buttons()
    {
        $buttons = new ButtonCollection(form(), html());

        $buttons->submit('Submit')->class('btn-primary');
        $buttons->reset('Reset');
        $buttons->button('Button');
        $buttons->link('link', 'Text');

        $expected = '<button type="submit" class="btn-primary">Submit</button>'
            .'<button type="reset">Reset</button>'
            .'<button type="button">Button</button>'
            .'<a href="http://localhost/link">Text</a>';

        $this->assertHtmlEquals($expected, $buttons->render());
    }
}