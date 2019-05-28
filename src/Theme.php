<?php

namespace Styde\Html;

use Illuminate\Contracts\View\Factory as View;

class Theme
{
    /**
     * Class used to render the views once we know which template to use.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;
    /**
     * Theme's name (i.e. bootstrap, foundation, custom, etc.)
     *
     * @var string
     */
    protected $currentTheme;

    /**
     * Directory to store the custom templates
     *
     * @var string
     */
    protected $publishedThemesDirectory;

    /**
     * Creates a Theme class, used to render custom or default templates for
     * any of the classes of this component (alert, menu, form, field)
     *
     * @param View $view
     * @param string $currentTheme
     * @param string $publishedThemesDirectory Directory to store the custom templates
     */
    public function __construct(View $view, string $currentTheme, string $publishedThemesDirectory = 'themes')
    {
        $this->view = $view;
        $this->currentTheme = $currentTheme;
        $this->publishedThemesDirectory = $publishedThemesDirectory;
    }

    /**
     * Get the name of this theme (i.e. bootstrap or foundation)
     *
     * @return string
     */
    public function getName()
    {
        return $this->currentTheme;
    }

    /**
     * Get the current view object
     *
     * @return \Illuminate\Contracts\View\Factory
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * Renders a custom template or one of the default templates.
     *
     * You can publish and customize the default template (resources/views/themes/)
     * or be located inside the components directory (vendor/styde/html/themes/).
     *
     * @param string $template
     * @param array $data
     * @return string
     */
    public function render($template, $data = array())
    {
        if (strpos($template, '@') === 0) {
            return $this->renderThemeTemplate(substr($template, 1), $data);
        } else {
            return $this->renderCustomTemplate($template, $data);
        }
    }

    public function renderThemeTemplate($template, $data)
    {
        if ($this->view->exists($this->getPublishedTemplate($template))) {
            return $this->renderPublishedTemplate($data, $template);
        } else {
            return $this->renderDefaultTemplate($data, $template);
        }
    }

    protected function renderPublishedTemplate($data, $template)
    {
        return $this->view->make($this->getPublishedTemplate($template), $data)->render();
    }

    protected function getPublishedTemplate($template)
    {
        return "{$this->publishedThemesDirectory}/{$this->currentTheme}/{$template}";
    }

    protected function renderDefaultTemplate($data, $template)
    {
        return $this->view->make("styde.html::{$this->currentTheme}/{$template}", $data)->render();
    }

    public function renderCustomTemplate($template, $data)
    {
        return $this->view->make($template, $data)->render();
    }
}
