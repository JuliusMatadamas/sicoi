<?php

namespace app\core;

class Controller
{
    public string $layout = 'main';

    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function render($view, $css, $js, $params = [])
    {
        return Application::$app->router->renderView($view, $css, $js, $params);
    }
}