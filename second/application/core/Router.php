<?php

class Router
{
    protected $routes;

    public function __construct($definitions)
    {
        $this->routes = $this->compileRoutes($definitions);
    }

    public function compileRoutes($definitions)
    {
        $routes = array();

        foreach ($definitions as $url => $params) {
            $tokens = explode('/', ltrim($url, '/'));
            foreach ($tokens as $i => $token) {
                if (strpos($token, ':') === 0) {
                    $name = substr($token, 1);
                    $token = '(?<' . $name . '>[^/]+)';
                }
                $tokens[$i] = $token;
            }

            $pattern = '/' . implode('/', $tokens);
            $routes[$pattern] = $params;
        }

        return $routes;
    }

    public function resolve($pathInfo)
    {
        if (substr($pathInfo, 0, 1) !== '/') {
            $pathInfo = '/' . $pathInfo;
        }

        foreach ($this->routes as $pattern => $params) {
            if (preg_match('#^' . $pattern . '$#', $pathInfo, $matches)) {
                $params = array_merge($params, $matches);

                return $params;
            }
        }

        return false;
    }
}