<?php

namespace BJ;

use Jenssegers\Blade\Blade;

abstract class AbstractController
{
    /**
     * Содержит обьект шаблонизатора.
     */
    protected $view;

    /**
     * Содержит заголовок страницы.
     */
    private $pageTitle;

    /**
     * Содержит описание страницы.
     */
    private $pageDescription;

    public function __construct()
    {
        $this->view = new Blade('views', 'cache/blade');
    }

    /**
     * Устанавливает заголовок страницы.
     * @param $value
     */
    protected function setPageTitle($value)
    {
        $this->pageTitle = $value;
    }

    /**
     * Устанавливает описание страницы.
     * @param $value
     */
    protected function setPageDescription($value)
    {
        $this->pageDescription = $value;
    }

    /**
     * Устанавливает описание страницы.
     * @param string $template имя шаблона
     * @param array $values переменные
     * @return string
     */
    protected function render($template, $values)
    {
        $this->view->share('pageTitle', $this->pageTitle);
        $this->view->share('pageDescription', $this->pageDescription);

        return $this->view->render($template, $values);
    }
}
