<?php

namespace Styde\Html;

use Styde\Html\Menu\Menu;
use Styde\Html\Menu\MenuGenerator;
use Styde\Html\Access\AccessHandler;
use Illuminate\Foundation\AliasLoader;
use Styde\Html\Alert\Container as Alert;
use Styde\Html\Access\BasicAccessHandler;
use Illuminate\Contracts\Auth\Access\Gate;
use Styde\Html\Alert\Middleware as AlertMiddleware;
use Collective\Html\HtmlServiceProvider as ServiceProvider;
use Styde\Html\Alert\SessionHandler as AlertSessionHandler;

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
     * @var AccessHandler
     */
    protected $accessHandler = null;
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;
    /**
     * Automatically assign the global aliases for the component's facades
     * @var bool
     */
    protected $globalAliases = [
        'Field' => \Styde\Html\Facades\Field::class,
        'Alert' => \Styde\Html\Facades\Alert::class,
        'Menu'  => \Styde\Html\Facades\Menu::class,
        'Form'  => \Collective\Html\FormFacade::class,
        'Html'  => \Collective\Html\HtmlFacade::class
    ];

    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../themes', 'styde.html');

        $this->publishes([
            __DIR__.'/../themes' => base_path('resources/views/themes'),
        ]);

        $this->publishes([
            __DIR__.'/../config.php' => config_path('html.php'),
        ]);
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
        parent::register();

        $this->registerAccessHandler();

        $this->registerFieldBuilder();

        $this->registerAlertContainer();
        $this->registerAlertMiddleware();

        $this->registerMenuGenerator();

        if (!empty($this->globalAliases)) {
            $this->registerFacadeAliases();
        }
    }

    /**
     * Load the configuration options from the config/html.php file.
     *
     * All the configuration options are optional, if they are not found, the
     * default configuration of this package will be used.
     */
    protected function loadConfigurationOptions()
    {
        if (! empty($this->options)) {
            return;
        }

        $this->mergeDefaultConfiguration();

        $this->options = $this->app->make('config')->get('html');

        $this->options['theme_values'] = $this->options['themes'][$this->options['theme']];
        unset($this->options['themes']);
    }

    /**
     * Instantiate and return the Theme object
     *
     * Only one theme is necessary, and the theme object will be used internally
     * by the other classes of this component. So we don't need to add it to the
     * IoC container.
     *
     * @return \Styde\Html\Theme
     */
    protected function getTheme()
    {
        if ($this->theme == null) {
            $this->theme = new Theme($this->app['view'], $this->options['theme']);
        }

        return $this->theme;
    }

    /**
     * Register the AccessHandler implementation into the IoC Container.
     * This package provides a BasicAccessHandler.
     *
     * @return \Styde\Html\Access\AccessHandler
     */
    protected function registerAccessHandler()
    {
        $this->app[AccessHandler::class] = $this->app->share(function ($app) {
            $handler = new BasicAccessHandler($app->make('auth')->driver());

            $gate = $app->make(Gate::class);
            if ($gate) {
                $handler->setGate($gate);
            }

            return $handler;
        });
    }

    /**
     * Register the Form Builder instance.
     */
    protected function registerFormBuilder()
    {
        $this->app['form'] = $this->app->share(function ($app) {

            $this->loadConfigurationOptions();

            $form = new FormBuilder(
                $app['html'],
                $app['url'],
                $app['session.store']->getToken(),
                $this->getTheme()
            );

            $form->novalidate(
                $app['config']->get('html.novalidate', false)
            );

            return $form->setSessionStore($app['session.store']);
        });
    }

    /**
     * Register the HTML Builder instance.
     */
    protected function registerHtmlBuilder()
    {
        $this->app->bindShared('html', function ($app) {
            return new HtmlBuilder($app['url']);
        });
    }

    /**
     * Register the Field Builder instance
     */
    protected function registerFieldBuilder()
    {
        $this->app->bind('field', function ($app) {

            $this->loadConfigurationOptions();

            $fieldBuilder = new FieldBuilder(
                $app['form'],
                $this->theme,
                $app['translator']
            );

            if ($this->options['control_access']) {
                $fieldBuilder->setAccessHandler($app[AccessHandler::class]);
            }

            $fieldBuilder->setAbbreviations($this->options['abbreviations']);

            if (isset($this->options['theme_values']['field_classes'])) {
                $fieldBuilder->setCssClasses(
                    $this->options['theme_values']['field_classes']
                );
            }

            if (isset($this->options['theme_values']['field_templates'])) {
                $fieldBuilder->setTemplates(
                    $this->options['theme_values']['field_templates']
                );
            }

            if ($app['session.store']->has('errors')) {
                $fieldBuilder->setErrors(
                    $app['session.store']->get('errors')->toArray()
                );
            }

            return $fieldBuilder;
        });
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
        $this->app->bindShared('alert', function ($app) {
            $this->loadConfigurationOptions();

            $alert = new Alert(
                $this->getAlertHandler(),
                $this->getTheme()
            );

            if ($this->options['translate_texts']) {
                $alert->setLang($app['translator']);
            }

            return $alert;
        });
    }

    /**
     * Register the Alert Middleware instance
     */
    protected function registerAlertMiddleware()
    {
        $this->app->bindShared(AlertMiddleware::class, function ($app) {
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

            $menu = new MenuGenerator(
                $app['url'],
                $app['config'],
                $this->getTheme()
            );

            if ($this->options['control_access']) {
                $menu->setAccessHandler($app[AccessHandler::class]);
            }

            if ($this->options['translate_texts']) {
                $menu->setLang($app['translator']);
            }

            return $menu;
        });
    }

    /**
     * Register the facade aliases for this component
     */
    protected function registerFacadeAliases()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            foreach ($this->globalAliases as $alias => $facade) {
                $loader->alias($alias, $facade);
            }
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
            AccessHandler::class,
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
