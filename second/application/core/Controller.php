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
}