<?php

namespace Styde\Html;

use Styde\Html\Menu\Menu;
use Styde\Html\Menu\MenuGenerator;
use Styde\Html\Menu\MenuMakeCommand;
use Illuminate\Support\ServiceProvider;
use Styde\Html\Alert\Container as Alert;
use Styde\Html\FormModel\FormMakeCommand;
use Styde\Html\Alert\Middleware as AlertMiddleware;
use Styde\Html\Alert\SessionHandler as AlertSessionHandler;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Array of options taken from the configuration file (config/html.php) and the default package configuration.
     *
     * @var array|null
     */
    protected $options;

    /**
     * Theme Object.
     *
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../themes', 'styde.html');

        $this->publishes([
            __DIR__.'/../themes' => base_path('resources/views/themes'),
        ], 'styde-html-theme');

        $this->publishes([
            __DIR__.'/../config.php' => config_path('html.php'),
        ], 'styde-html-config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerHtmlBuilder();

        $this->registerFormBuilder();

        $this->registerThemeClass();

        $this->registerFieldRenderer();

        $this->registerFormFieldBuilder();

        $this->registerMakeFormCommand();

        $this->registerAlertContainer();

        $this->registerAlertMiddleware();

        $this->registerMenuGenerator();
    }

    /**
     * Load the configuration options from the config/html.php file. All the configuration options are optional,
     * if they are not found, the default configuration of this package will be used.
     *
     * @return void
     */
    protected function loadConfigurationOptions()
    {
        if ($this->options != null) {
            return;
        }

        $this->mergeConfigFrom(__DIR__.'/../config.php', 'html');

        $this->options = $this->app->make('config')->get('html');

        $this->options['theme_values'] = $this->options['themes'][$this->options['theme']];

        unset($this->options['themes']);
    }

    /**
     * Register the Theme object into the IoC container.
     *
     * @return void
     */
    protected function registerThemeClass()
    {
        $this->app->singleton('html.theme', function ($app) {
            $this->loadConfigurationOptions();

            return new Theme($this->app['view'], $this->options['theme'], $this->options['custom']);
        });

        $this->app->alias('html.theme', Theme::class);
    }

    /**
     * Register the Form Builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $form = new FormBuilder($app['html.theme'], $app['session.store']);

            $form->novalidate($app['config']->get('html.novalidate', false));

            return $form;
        });

        $this->app->alias('form', FormBuilder::class);
    }

    /**
     * Register the HTML Builder instance.
     *
     * @return void
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new HtmlBuilder($app['url'], $app['view']);
        });

        $this->app->alias('html', HtmlBuilder::class);
    }

    /**
     * Register the Field Builder instance.
     *
     * @return void
     */
    protected function registerFormFieldBuilder()
    {
        $this->app->bind('field', function () {
            return new FormFieldBuilder;
        });

        $this->app->alias('field', FormFieldBuilder::class);
    }

    /**
     * Register the Field Builder instance.
     *
     * @return void
     */
    protected function registerFieldRenderer()
    {
        $this->app->singleton('field.renderer', function ($app) {
            $this->loadConfigurationOptions();

            $fieldBuilder = new FieldRenderer($app['form'], $app['html.theme'], $app['translator']);

            if (isset($this->options['theme_values']['field_templates'])) {
                $fieldBuilder->setTemplates($this->options['theme_values']['field_templates']);
            }

            if (isset($this->options['theme_values']['field_classes'])) {
                $fieldBuilder->classes($this->options['theme_values']['field_classes']);
            }

            $fieldBuilder->setSessionStore($app['session.store']);

            return $fieldBuilder;
        });

        $this->app->alias('field.renderer', FieldRenderer::class);
    }

    /**
     * Register the make:form command.
     *
     * @return void
     */
    public function registerMakeFormCommand()
    {
        $this->commands(FormMakeCommand::class);
        $this->commands(MenuMakeCommand::class);
    }

    /**
     * Get the Alert handler implementation used to persist alert messages.
     *
     * This will be used internally by the Alert Container class so we don't to add this class to the IoC container.
     *
     * @return \Styde\Html\Alert\SessionHandler
     */
    protected function getAlertHandler()
    {
        return new AlertSessionHandler($this->app['session.store'], 'styde/alerts');
    }

    /**
     * Register the Alert Container instance.
     *
     * @return void
     */
    protected function registerAlertContainer()
    {
        $this->app->singleton('alert', function ($app) {
            $this->loadConfigurationOptions();

            $alert = new Alert($this->getAlertHandler(), $app['html.theme']);

            $alert->setLang($app['translator']);

            return $alert;
        });

        $this->app->alias('alert', Alert::class);
    }

    /**
     * Register the Alert Middleware instance.
     *
     * @return void
     */
    protected function registerAlertMiddleware()
    {
        $this->app->singleton(AlertMiddleware::class, function ($app) {
            return new AlertMiddleware($app['alert']);
        });
    }

    /**
     * Register the Menu Generator instance.
     */
    protected function registerMenuGenerator()
    {
        $this->app->bind('menu', function ($app) {
            return new MenuGenerator($app['url'], $app['html.theme']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            HtmlBuilder::class, FormBuilder::class, FormFieldBuilder::class,
            Alert::class, AlertMiddleware::class, Menu::class,
            'html', 'form', 'field', 'alert', 'menu'
        ];
    }
}
