<?php

namespace Styde\Html\Tests;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Styde\Html\{Form, FormModel};
use Illuminate\Support\Facades\{
    Gate, View, Route
};
use Styde\Html\FormModel\{FieldCollection, ButtonCollection};
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableInterface;

class FormModelTest extends TestCase
{
    /** @test */
    function it_builds_a_login_form()
    {
        Route::post('login', ['as' => 'login']);

        $loginForm = app(LoginForm::class);

        $this->assertTemplateMatches('form-model/login-form', $loginForm);
    }

    /** @test */
    function can_use_another_template()
    {
        View::addLocation(__DIR__.'/views');

        $formModel = app(CustomTemplateForm::class)->template('custom-templates/form-model');

        $this->assertHtmlEquals('<p>Custom template</p>', $formModel);
    }

    /** @test */
    function it_returns_the_rules_from_all_fields()
    {
        $rules = app(RegisterForm::class)->getValidationRules();

        $expect = [
            'name' => [],
            'email' => ['email', 'unique:users', 'required'],
            'password' => ['confirmed', 'min:6', 'max:12', 'required'],
            'password_confirmation' => ['min:6', 'max:12', 'required']
        ];

        $this->assertSame($expect, $rules);
    }

    /** @test */
    function it_returns_the_rules_that_dont_require_authentication()
    {
        $form = app(PostForm::class);

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
        $form = app(PostForm::class);

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
        $form = app(PostForm::class);

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
        $form = app(PostForm::class);

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
        $form = app(PostForm::class);

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

class LoginForm extends FormModel
{
    /**
     * Setup the form attributes, fields and buttons.
     *
     * @return void
     */
    public function setup()
    {
        $this->route('login')->role('form');

        $this->email('email');
        $this->password('password');
        $this->checkbox('remember_me');

        $this->submit(trans('auth.login_action'))->classes('btn btn-primary');
        $this->link(url('password/email'), trans('auth.forgot_link'));
    }
}

class CustomTemplateForm extends FormModel
{
    //...
}

class RegisterForm extends FormModel
{
    /**
     * Setup the form attributes, fields and buttons.
     *
     * @return void
     */
    public function setup()
    {
        $this->text('name')->required()->disableRules();
        $this->email('email')->unique('users')->required();
        $this->password('password')->confirmed()->min(6)->max(12)->required();
        $this->password('password_confirmation')->min(6)->max(12)->required();
    }
}

class PostForm extends FormModel
{
    //...
}