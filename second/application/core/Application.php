<?php

abstract class Application
{
    protected $debug = false;
    protected $request;
    protected $response;
    protected $session;
    protected $dbManager;
    protected $loginAction = array();

    /**
     * @param bool $debug
     */
    public function __construct($debug = false)
    {
        $this->setDebugMode($debug);
        $this->initialize();
        $this->configure();
    }

    /**
     * @param bool $debug
     */
    protected function setDebugMode($debug)
    {
        if ($debug) {
            $this->debug = true;
            ini_set('display_errors', 1);
            error_reporting(-1);
        } else {
            $this->debug = false;
            ini_set('display_errors', 0);
        }
    }

    protected function initialize()
    {
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->dbManager = new DbManager();
        $this->router = new Router($this->registerRoutes());
    }

    protected function configure()
    {
    }

    abstract public function getRootDir();

    abstract protected function registerRoutes();

    /**
     * @return bool
     */
    public function isDebugMode()
    {
        return $this->debug;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * @return DbManager
     */
    public function getDbManager()
    {
        return $this->dbManager;
    }

    /**
     * @return string
     */
    public function getControllerDir()
    {
        return $this->getRootDir() . '/controllers';
    }

    /**
     * @return string
     */
    public function getViewDir()
    {
        return $this->getRootDir() . '/views';
    }

    /**
     * @return string
     */
    public function getModelDir()
    {
        return $this->getRootDir() . '/models';
    }

    public function run()
    {
        try {
            $params = $this->router->resolve($this->request->getPathInfo());
            if ($params === false) {
                throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];
            $this->runAction($controller, $action, $params);

        } catch (HttpNotFoundException $e) {
            $this->render404Page($e);

        } catch (UnauthorizedActionException $e) {
            list($controller, $action) = $this->loginAction;
            $this->runAction($controller, $action);
        }

        $this->response->send();
    }

    /**
     * @param string $controllerName
     * @param string $action
     * @param array $params
     * @throws Exception not found controller
     */
    public function runAction($controllerName, $action, $params = array())
    {
        $controllerClass = ucfirst($controllerName) . 'Controller';

        $controller = $this->findController($controllerClass);
        if ($controller === false) {
            throw new HttpNotFoundException($controllerClass . ' controller is not found.');
        }

        $content = $controller->run($action, $params);

        $this->response->setContent($content);
    }

    /**
     * @param string $controllerClass
     * @return class|false
     */
    protected function findController($controllerClass)
    {
        if(!class_exists($controllerClass)) {
            $controllerFile = $this->getControllerDir() . '/' . $controllerClass . '.php';

            if (!is_readable($controllerFile)) {
                return false;
            } else {
                require_once $controllerFile;

                if (!class_exists($controllerClass)) {
                    return false;
                }
            }
        }

        return new $controllerClass($this);
    }

    /**
     * @param error $e
     */
    protected function render404Page($e)
    {
        $this->response->setStatusCode(404, 'Not Found');
        $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
        $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

        $this->response->setContent(<<<EOF
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>404</title>
</head>
<body>
  {$message}
</body>
</html>
EOF
        );
    }
}