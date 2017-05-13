<?php

abstract class Controller
{
    protected $controllerName;
    protected $actionName;
    protected $application;
    protected $request;
    protected $response;
    protected $session;
    protected $dbManager;
    protected $authActions = array();

    /**
     * Controller constructor.
     * @param class $application
     */
    public function __construct($application)
    {
        $this->controllerName = strtolower(substr(get_class($this), 0, -10));

        $this->application = $application;
        $this->request     = $application->getRequest();
        $this->response    = $application->getResponse();
        $this->session     = $application->getSession();
        $this->dbManager   = $application->getDbManager();
    }

    /**
     * @param string $action
     * @param array $params
     * @return mixed
     */
    public function run($action, $params = array())
    {
        $this->actionName = $action;

        $actionMethod = $action . 'Action';
        if (!method_exists($this, $actionMethod)) {
            $this->forward404();
        }

        if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
            throw new UnauthorizedActionException();
        }

        $content = $this->actionMethod($params);
        return $content;
    }

    /**
     * @param array $variables
     * @param string $template
     * @param string $layout
     * @return string
     */
    protected function render($variables = array(), $template = null, $layout = 'layout')
    {
        $defaults = array(
            'request'  => $this->request,
            'base_url' => $this->request->getBaseUrl(),
            'session'  => $this->session,
        );

        $view = new View($this->application->getViewDir(), $defaults);

        if (is_null($template)) {
            $template = $this->actionName;
        }

        $path = $this->controllerName . '/' . $template;

        return $view->render($path, $variables, $layout);
    }

    protected function forward404()
    {
        throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controllerName . '/' . $this->actionName);
    }

    /**
     * @param string $url
     */
    protected function redirect($url)
    {
        if (!preg_match('#https?://#', $url)) {
            $protocol = $this->request->isSsl() ? 'https://' : 'http://';
            $host     = $this->request->getHost();
            $baseUrl  = $this->request->getBaseUrl();

            $url = $protocol . $host . $baseUrl . $url;
        }

        $this->response->setStatusCode('302', 'Found');
        $this->response->setHttpHeader('Location', $url);
    }

    /**
     * @param string $formName
     * @return string
     */
    protected function generateCsrfToken($formName)
    {
        $key = 'csrf_tokens/' . $formName;
        $tokens = $this->session->get($key, array());
        if (count($tokens) >= 10) {
            array_shift($tokens);
        }

        $token = sha1($formName . session_id() . microtime());
        $tokens[] = $token;

        $this->session->set($key, $tokens);
        return $token;
    }

    /**
     * @param string $formName
     * @param string $token
     * @return bool
     */
    protected function checkCsrfToken($formName, $token)
    {
        $key = 'csrf_tokens/' . $formName;
        $tokens = $this->session->get($key, array());

        if ($pos = array_search($token, $tokens, true)) {
            unset($tokens[$pos]);
            $this->session->set($key, $tokens);

            return true;

        } else {
            return false;
        }
    }

    /**
     * @param string $action
     * @return bool
     */
    protected function needsAuthentication($action)
    {
        if ($this->authActions === true
            || (is_array($this->authActions) && in_array($action, $this->authActions))
        ) {
            return true;

        } else {
            return false;
        }
    }
}