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
    function it_returns_all_rules_of_fields()
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

        $form->fields->email('email')->required();
        $form->fields->text('description')->required()->ifAuth();

        $expect = [
            'email' => ['email', 'required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_that_dont_require_a_guest_user()
    {
        $form = app(PostForm::class);

        $this->actingAs($this->getUser());

        $form->fields->email('email')->required();
        $form->fields->text('description')->required()->ifGuest();

        $expect = [
            'email' => ['email', 'required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_from_fields_with_authorization()
    {
        $form = app(PostForm::class);

        $this->actingAs($this->getUser());

        Gate::define('edit-all', function ($user) {
            return true;
        });

        $form->fields->email('email')->required();
        $form->fields->text('description')->required()->ifCan('edit-all');

        $expect = [
            'email' => ['email', 'required'],
            'description' => ['required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_the_rules_from_fields_withot_authorization()
    {
        $form = app(PostForm::class);

        $this->actingAs($this->getUser());

        Gate::define('admin', function ($user) {
            return false;
        });

        $form->fields->email('email')->required();
        $form->fields->text('description')->required()->ifCannot('admin');

        $expect = [
            'email' => ['email', 'required'],
            'description' => ['required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }

    /** @test */
    function it_returns_rules_of_fields_with_ifis_method()
    {
        $form = app(PostForm::class);

        $fakeUser = new class extends Model implements AuthenticatableInterface {
            use Authenticatable;

            public function isA($role)
            {
                return $role == 'admin';
            }
        };

        $this->actingAs($fakeUser);

        $form->fields->email('email')->required();
        $form->fields->text('description')->required()->ifIs('admin');

        $expect = [
            'email' => ['email', 'required'],
            'description' => ['required']
        ];

        $this->assertEquals($expect, $form->getValidationRules());
    }
}

class LoginForm extends FormModel
{
    public $method = 'post';

    /**
     * Setup the form attributes and buttons.
     *
     * @param \Styde\Html\Form $form
     * @param \Styde\Html\FormModel\ButtonCollection $buttons
     * @return void
     */
    public function setup(Form $form, ButtonCollection $buttons)
    {
        $form->route('login')->role('form');

        $buttons->submit(trans('auth.login_action'))->classes('btn btn-primary');
        $buttons->link(url('password/email'), trans('auth.forgot_link'));
    }

    /**
     * Setup the form fields.
     *
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @return void
     */
    public function fields(FieldCollection $fields)
    {
        $fields->email('email');
        $fields->password('password');
        $fields->checkbox('remember_me');
    }
}

class CustomTemplateForm extends FormModel
{
    /**
     * Setup the form attributes, fields and buttons.
     *
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @return void
     */
    public function fields(FieldCollection $fields)
    {
        // TODO: Implement setup() method.
    }
}

class RegisterForm extends FormModel
{
    /**
     * Setup the form fields.
     *
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @return void
     */
    public function fields(FieldCollection $fields)
    {
        $fields->text('name')->required()->disableRules();
        $fields->email('email')->unique('users')->required();
        $fields->password('password')->confirmed()->min(6)->max(12)->required();
        $fields->password('password_confirmation')->min(6)->max(12)->required();
    }
}

class PostForm extends FormModel
{
    /**
     * Setup the form fields.
     *
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @return void
     */
    public function fields(FieldCollection $fields)
    {
        //
    }
}