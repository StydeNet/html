<?php

namespace Styde\Html\Tests;

use Styde\Html\{FormElement, FormModel};
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
}

class LoginForm extends FormModel
{
    public $method = 'post';

    /**
     * Setup the form attributes, fields and buttons.
     *
     * @param \Styde\Html\FormElement $form
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @param \Styde\Html\FormModel\ButtonCollection $buttons
     * @return void
     */
    public function setup(FormElement $form, FieldCollection $fields, ButtonCollection $buttons)
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
     * @param \Styde\Html\FormElement $form
     * @param \Styde\Html\FormModel\FieldCollection $fields
     * @param \Styde\Html\FormModel\ButtonCollection $buttons
     * @return void
     */
    public function setup(FormElement $form, FieldCollection $fields, ButtonCollection $buttons)
    {
        // TODO: Implement setup() method.
    }
}
