<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Html;
use Illuminate\Support\Facades\Gate;

class HtmlAccessTest extends TestCase
{
    /** @test */
    function it_only_renders_the_tag_if_the_user_has_the_expected_role()
    {
        $tag = Html::tag('p', 'This is the content.');

        $this->assertNull($tag->ifIs('admin')->render());

        $this->actingAs($this->aUser());
        $this->assertNull($tag->ifIs('admin')->render());

        $this->actingAs($this->anAdmin());
        $this->assertNotNull($tag->ifIs('admin')->render());
    }

    /** @test */
    function it_only_renders_the_void_tag_if_the_user_has_the_expected_role()
    {
        $tag = Html::tag('hr');

        $this->assertNull($tag->ifIs('admin')->render());

        $this->actingAs($this->aUser());
        $this->assertNull($tag->ifIs('admin')->render());

        $this->actingAs($this->anAdmin());
        $this->assertNotNull($tag->ifIs('admin')->render());
    }

    /** @test */
    function it_only_renders_the_tag_if_the_user_is_not_guest()
    {
        $tag = Html::tag('p', 'This is the content.');

        $this->assertNotNull($tag->ifGuest()->render());

        $this->actingAs($this->aUser());
        $this->assertNull($tag->ifGuest()->render());
    }

    /** @test */
    function it_only_renders_the_void_tag_if_the_user_is_not_guest()
    {
        $tag = Html::tag('br');

        $this->assertNotNull($tag->ifGuest()->render());

        $this->actingAs($this->aUser());
        $this->assertNull($tag->ifGuest()->render());
    }

    /** @test */
    function if_only_renders_the_tag_if_user_is_logged_in()
    {
        $tag = Html::tag('p', 'This is the content.');

        $this->assertNull($tag->ifAuth()->render());

        $this->actingAs($this->aUser());
        $this->assertNotNull($tag->ifAuth()->render());
    }

    /** @test */
    function if_only_renders_the_void_tag_if_user_is_logged_in()
    {
        $tag = Html::tag('br');

        $this->assertNull($tag->ifAuth()->render());

        $this->actingAs($this->aUser());
        $this->assertNotNull($tag->ifAuth()->render());
    }

    /** @test */
    function it_only_renders_the_tag_if_the_user_has_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $tag = Html::tag('p', 'This is the content.');

        $this->assertNull($tag->ifCan('edit-all')->render());

        $this->assertNotNull($tag->ifCan('edit-mine')->render());
    }

    /** @test */
    function it_only_renders_the_tag_if_the_user_does_not_have_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $tag = Html::tag('p', 'This is the content.');

        $this->assertNotNull($tag->ifCannot('edit-all')->render());

        $this->assertNull($tag->ifCannot('edit-mine')->render());
    }

    /** @test */
    function it_only_renders_the_void_tag_if_the_user_has_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $tag = Html::tag('br');

        $this->assertNull($tag->ifCan('edit-all')->render());

        $this->assertNotNull($tag->ifCan('edit-mine')->render());
    }

    /** @test */
    function it_only_renders_the_void_tag_if_the_user_does_not_have_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $tag = Html::tag('br');

        $this->assertNotNull($tag->ifCannot('edit-all')->render());

        $this->assertNull($tag->ifCannot('edit-mine')->render());
    }
}
