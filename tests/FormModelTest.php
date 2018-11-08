<?php

namespace Styde\Html\Tests;

use Styde\Html\FormModel;
use Illuminate\Support\Facades\{View, Route};

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
    function it_gets_the_fields_by_name()
    {
        $form = app(FormModel::class);

        $form->text('name');

        $this->assertInstanceOf(FormModel\Field::class, $form->name);
        $this->assertSame('text', $form->name->type);
        $this->assertSame('name', $form->name->name);
    }

    /** @test */
    function can_use_a_customized_template()
    {
        View::addLocation(__DIR__.'/views');

        $formModel = app(FormModel::class)->template('custom-templates/form-model');

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
            'password_confirmation' => ['min:6', 'max:12', 'required'],
            'remember_me' => ['required']
        ];

        $this->assertSame($expect, $rules);
    }

    /** @test */
    function it_returns_the_rules_only_for_fields()
    {
        $form = app(FormModel::class);

        $form->tag('h3', 'title here');
        $form->email('email')->required();
        
        $expect = [
            'email' => ['email', 'required']
        ];

        $this->assertSame($expect, $form->getValidationRules());
    }

    /** @test */
    function it_builds_a_update_form()
    {
        $userModel = $this->aUserWithData([
            'name' => 'Clemir',
            'email' => 'clemir@styde.net'
        ]);

        $userForm = app(UserForm::class)->model($userModel)->forUpdate();

        $this->assertTemplateMatches('form-model/user-form-for-update', $userForm);
    }
}

class LoginForm extends FormModel
{
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

class RegisterForm extends FormModel
{
    public function setup()
    {
        $this->text('name')->required()->disableRules();
        $this->email('email')->unique('users')->required();
        $this->password('password')->confirmed()->min(6)->max(12)->required();
        $this->password('password_confirmation')->min(6)->max(12)->required();
        $this->checkbox('remember_me')->required();
    }
}

class UserForm extends FormModel
{
    public function setup()
    {
        $this->text('name');
        $this->email('email');
    }

    public function updateSetup()
    {
        $this->submit('Update user');
    }
}
