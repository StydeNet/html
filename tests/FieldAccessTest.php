<?php

namespace Styde\Html\Tests;

use Styde\Html\Facades\Field;
use Illuminate\Support\Facades\Gate;

class FieldAccessTest extends TestCase
{
    /** @test */
    function it_only_renders_the_field_if_the_user_has_the_expected_role()
    {
        $field = Field::text('name');

        $this->assertNull($field->ifIs('admin')->render());

        $this->actingAs($this->aUser());
        $this->assertNull($field->ifIs('admin')->render());

        $this->actingAs($this->anAdmin());
        $this->assertNotNull($field->ifIs('admin')->render());
    }

    /** @test */
    function it_only_renders_the_field_if_the_user_is_not_guest()
    {
        $field = Field::text('name');

        $this->assertNotNull($field->ifGuest()->render());

        $this->actingAs($this->aUser());
        $this->assertNull($field->ifGuest()->render());
    }

    /** @test */
    function if_only_renders_the_field_if_user_is_logged_in()
    {
        $field = Field::text('name');

        $this->assertNull($field->ifAuth()->render());

        $this->actingAs($this->aUser());
        $this->assertNotNull($field->ifAuth()->render());
    }

    /** @test */
    function it_only_renders_the_field_if_the_user_has_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $field = Field::text('name');

        $this->assertNull($field->ifCan('edit-all')->render());

        $this->assertNotNull($field->ifCan('edit-mine')->render());
    }

    /** @test */
    function it_only_renders_the_field_if_the_user_does_not_have_the_given_ability()
    {
        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return false;
        });

        Gate::define('edit-mine', function ($user) {
            return true;
        });

        $field = Field::text('name');

        $this->assertNotNull($field->ifCannot('edit-all')->render());

        $this->assertNull($field->ifCannot('edit-mine')->render());
    }
}