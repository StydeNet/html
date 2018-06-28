<?php

namespace Styde\Html;

use Illuminate\Support\ServiceProvider;
use Styde\Html\Alert\Container as Alert;
use Styde\Html\Alert\Middleware as AlertMiddleware;
use Styde\Html\Alert\SessionHandler as AlertSessionHandler;
use Styde\Html\FormModel\FormMakeCommand;
use Styde\Html\Menu\Menu;
use Styde\Html\Menu\MenuGenerator;
use Styde\Html\Menu\MenuMakeCommand;

class HtmlServiceProvider extends ServiceProvider
{
    /**
     * Array of options taken from the configuration file (config/html.php) and
     * the default package configuration.
     *
     * @var array
     */
    protected $options;

    /**
     * @var \Styde\Html\Theme
     */
    protected $theme;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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

    protected function mergeDefaultConfiguration()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config.php', 'html'
        );
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerHtmlBuilder();

        $this->registerFormBuilder();

        $this->registerThemeClass();

        $this->registerFieldBuilder();

        $this->registerMakeFormCommand();

        $this->registerAlertContainer();
        $this->registerAlertMiddleware();

        $this->registerMenuGenerator();
    }

    /**
     * Load the configuration options from the config/html.php file.
     *
     * All the configuration options are optional, if they are not found, the
     * default configuration of this package will be used.
     */
    protected function loadConfigurationOptions()
    {
        if ( ! empty($this->options)) return;

        $this->mergeDefaultConfiguration();

        $this->options = $this->app->make('config')->get('html');

        $this->options['theme_values'] = $this->options['themes'][$this->options['theme']];
        unset ($this->options['themes']);
    }

    /**
     * Register the Theme object into the IoC container
     *
     * @return \Styde\Html\Theme
     */
    protected function registerThemeClass()
    {
       $this->app->singleton(Theme::class, function ($app) {
            return new Theme($this->app['view'], $this->options['theme'], $this->options['custom']);
        });
    }

    /**
     * Register the Form Builder instance.
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function ($app) {
            $this->loadConfigurationOptions();

            $form = new FormBuilder($app['url'], $app->make(Theme::class), $app['session.store']);

            $form->novalidate($app['config']->get('html.novalidate', false));

            return $form;
        });

        $this->app->alias('form', FormBuilder::class);
    }

    /**
     * Register the HTML Builder instance.singlenotsinntrntrn
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function ($app) {
            return new HtmlBuilder($app['url'], $app['view']);
        });

        $this->app->alias('html', HtmlBuilder::class);
    }

    /**
     * Register the Field Builder instance
     */
    protected function registerFieldBuilder()
    {
        $this->app->bind('field', function ($app) {

            $this->loadConfigurationOptions();

            $fieldBuilder = new FieldBuilder(
                $app['form'], $app->make(Theme::class), $app['translator']
            );

            if (isset ($this->options['theme_values']['field_templates'])) {
                $fieldBuilder->setTemplates(
                    $this->options['theme_values']['field_templates']
                );
            }

            $fieldBuilder->setSessionStore($app['session.store']);

            return $fieldBuilder;
        });

        $this->app->alias('field', FieldBuilder::class);
    }

    public function registerMakeFormCommand()
    {
        $this->commands(FormMakeCommand::class);
        $this->commands(MenuMakeCommand::class);
    }

    /**
     * Get the Alert handler implementation used to persist alert messages.
     *
     * This will be used internally by the Alert Container class so we don't
     * need to add this class to the IoC container.
     *
     * @return AlertSessionHandler
     */
    protected function getAlertHandler()
    {
        return new AlertSessionHandler(
            $this->app['session.store'],
            'styde/alerts'
        );
    }

    /**
     * Register the Alert Container instance
     */
    protected function registerAlertContainer()
    {
        $this->app->singleton('alert', function ($app) {
            $this->loadConfigurationOptions();

            $alert = new Alert(
                $this->getAlertHandler(),
                $app->make(Theme::class)
            );

            if ($this->options['translate_texts']) {
                $alert->setLang($app['translator']);
            }

            return $alert;
        });

        $this->app->alias('alert', Alert::class);
    }

    /**
     * Register the Alert Middleware instance
     */
    protected function registerAlertMiddleware()
    {
        $this->app->singleton(AlertMiddleware::class, function ($app) {
            return new AlertMiddleware($app['alert']);
        });
    }

    /**
     * Register the Menu Generator instance
     */
    protected function registerMenuGenerator()
    {
        $this->app->bind('menu', function ($app) {
            $this->loadConfigurationOptions();

            $menu = new MenuGenerator($app['url'], $app[Theme::class]);

            return $menu;
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
            HtmlBuilder::class,
            FormBuilder::class,
            FieldBuilder::class,
            Alert::class,
            AlertMiddleware::class,
            Menu::class,
            'html',
            'form',
            'field',
            'alert',
            'menu'
        ];
    }
}
