<?php

namespace Styde\Html\Tests;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Styde\Html\FormModel;

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
}

class LoginForm extends FormModel
{
    public $method = 'post';

    public function setup()
    {
        $this->form->route('login')->role('form');

        $this->fields->email('email');
        $this->fields->password('password');
        $this->fields->checkbox('remember_me');

        $this->buttons->submit(trans('auth.login_action'))->classes('btn btn-primary');
        $this->buttons->link(url('password/email'), trans('auth.forgot_link'));
    }
}

class CustomTemplateForm extends FormModel {
    /**
     * Setup the form attributes, adds the fields and buttons.
     */
    public function setup()
    {
    }
}
