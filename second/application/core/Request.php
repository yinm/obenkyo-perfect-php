<?php

class Request
{
    /**
     * @return bool
     */
    public function isPost()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $name
     * @param null|string $default
     * @return string
     */
    public function getGet($name, $default = null)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }

        return $default;
    }

    /**
     * @param string $name
     * @param null|string $default
     * @return string
     */
    public function getPost($name, $default = null)
    {
        if (isset($_POST[$name])) {
            return $_POST[$name];
        } else {
            return $default;
        }
    }

    /**
     * @return string
     */
    public function getHost()
    {
        if (!empty($_SERVER['HTTP_HOST'])) {
            return $_SERVER['HTTP_HOST'];
        } else {
            return $_SERVER['SERVER_NAME'];
        }
    }

    /**
     * @return bool
     */
    public function isSsl()
    {
        if (!isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return string
     */
    public function getRequestUri()
    {
        return $_SERVER['REQUEST_URI'];
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $requestUri = $this->getRequestUri();

        if (strpos($requestUri, $scriptName) === 0) {
            return $scriptName;
        } else if (strpos($requestUri, dirname($scriptName))) {
            return rtrim(dirname($scriptName), '/');
        } else {
            return '';
        }
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        $baseUrl = $this->getBaseUrl();
        $requestUri = $this->getRequestUri();

        if ($pos = strpos($requestUri, '?')) {
            $requestUri = substr($requestUri, 0, $pos);
        }

        $pathInfo = (string)substr($requestUri, strlen($baseUrl));
        return $pathInfo;
    }
}