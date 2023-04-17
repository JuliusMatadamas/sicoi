<?php

namespace app\core;

class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    /**
     * 00005
     * Se obtiena la ruta, luego el método
     * @return void
     */
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $callback = $this->routes[$method][$path] ?? false;
//        echo "<pre>";
//        var_dump($callback);
//        echo "</pre>";
//        exit;
        if ($callback === false)
        {
            $this->response->setStatusCode(404);
            Response::redirect('/_404');
        }

        if (is_string($callback))
        {
            return $this->renderView($callback, '', '', '');
        }

        if (is_array($callback))
        {
            Application::$app->controller = new $callback[0]();
            $callback[0] = Application::$app->controller;
        }

        return call_user_func($callback, $this->request);
    }

    public function renderView(string $view, string $css, string $js, array $params = [])
    {
        $layoutContent = $this->layoutContent();
        $viewLayout = $this->renderOnlyView($view, $params);
        $viewContent = str_replace('{{content}}', $viewLayout, $layoutContent);
        $viewCss = str_replace('{{css}}', $css, $viewContent);
        $viewJs = str_replace('{{js}}', $js, $viewCss);
        return $viewJs;
    }

    protected function layoutContent()
    {
        $layout = Application::$app->controller->layout;
        ob_start();
        include_once Application::$ROOT_DIR . "/views/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $data)
    {
        foreach ($data as $key => $value)
        {
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR . "/views/$view.php";
        return ob_get_clean();
    }
}