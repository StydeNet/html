<?php

namespace Styde\Html\Tests;

use Styde\Html\FormModel;
use Illuminate\Support\Facades\Gate;

class FormModelAccessTest extends TestCase
{
    /** @test */
    function it_returns_the_rules_that_dont_require_authentication()
    {
        $form = app(FormModel::class);

        $form->email('email')->required();
        $form->text('description')->required()->ifAuth();

        $expect = [
            'email' => ['email', 'required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_that_dont_require_a_guest_user()
    {
        $form = app(FormModel::class);

        $this->actingAs($this->aUser());

        $form->email('email')->required();
        $form->text('description')->required()->ifGuest();

        $expect = [
            'email' => ['email', 'required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_from_fields_with_authorization()
    {
        $form = app(FormModel::class);

        $this->actingAs($this->aUser());

        Gate::define('edit-all', function ($user) {
            return true;
        });

        $form->email('email')->required();
        $form->text('description')->required()->ifCan('edit-all');

        $expect = [
            'email' => ['email', 'required'],
            'description' => ['required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_from_fields_without_authorization()
    {
        $form = app(FormModel::class);

        $this->actingAs($this->aUser());

        Gate::define('admin', function ($user) {
            return false;
        });

        $form->email('email')->required();
        $form->text('description')->required()->ifCannot('admin');

        $expect = [
            'email' => ['email', 'required'],
            'description' => ['required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_from_fields_with_role_authorization()
    {
        $form = app(FormModel::class);

        $this->actingAs($this->anEditor());

        $form->email('email')->required();
        $form->text('description')->required()->ifIs('editor');
        $form->text('published')->required()->ifIs('admin');

        $expect = [
            'email' => ['email', 'required'],
            'description' => ['required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }
}
