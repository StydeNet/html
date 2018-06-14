<?php

namespace Styde\Html\Tests;

use Styde\Html\{Form, FormModel};
use Illuminate\Support\Facades\{View, Route};
use Styde\Html\FormModel\{FieldCollection, ButtonCollection};

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

        $this->assertEquals($expect, $rules);
    }
}

class LoginForm extends FormModel
{
    public $method = 'post';

    /**
     * Setup the form attributes, fields and buttons.
     *
     * @param \Styde\Html\Form $form
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @param \Styde\Html\FormModel\ButtonCollection $buttons
     * @return void
     */
    public function setup(Form $form, FieldCollection $fields, ButtonCollection $buttons)
    {
        $form->route('login')->role('form');

        $fields->email('email');
        $fields->password('password');
        $fields->checkbox('remember_me');

        $buttons->submit(trans('auth.login_action'))->classes('btn btn-primary');
        $buttons->link(url('password/email'), trans('auth.forgot_link'));
    }
}

class CustomTemplateForm extends FormModel {
    /**
     * Setup the form attributes, fields and buttons.
     *
     * @param \Styde\Html\Form $form
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @param \Styde\Html\FormModel\ButtonCollection $buttons
     * @return void
     */
    public function setup(Form $form, FieldCollection $fields, ButtonCollection $buttons)
    {
        // TODO: Implement setup() method.
    }
}

class RegisterForm extends FormModel
{
    public $method = 'post';

    /**
     * Setup the form attributes, fields and buttons.
     *
     * @param \Styde\Html\Form $form
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @param \Styde\Html\FormModel\ButtonCollection $buttons
     * @return void
     */
    public function setup(Form $form, FieldCollection $fields, ButtonCollection $buttons)
    {
        $fields->text('name')->required()->disableRules();
        $fields->email('email')->unique('users')->required();
        $fields->password('password')->confirmed()->min(6)->max(12)->required();
        $fields->password('password_confirmation')->min(6)->max(12)->required();
    }
}
