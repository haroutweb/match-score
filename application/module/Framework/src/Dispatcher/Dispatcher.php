<?php
namespace Framework\Dispatcher;

use Framework\Container\Container;
use Framework\Dispatcher\Exception\ResponseInvalidParameter;
use Framework\Mvc\ViewModel;
use Framework\Response\Response;

class Dispatcher
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @param Container $container
     * @throws ResponseInvalidParameter
     */
    public function dispatch(Container $container)
    {
        /**
         * @var Response $response
         */
        $this->container = $container;
        $response        = $container->get('response');

        if (is_null($response->getModule())) {
            throw new ResponseInvalidParameter('Response module has\'nt defined');
        }

        if (is_null($response->getController())) {
            throw new ResponseInvalidParameter('Response controller has\'nt defined');
        }

        if (is_null($response->getAction())) {
            throw new ResponseInvalidParameter('Response action has\'nt defined');
        }

        $this->loadModule($response);
    }

    /**
     * @param Response $response
     * @return mixed
     * @throws ResponseInvalidParameter
     */
    private function loadModule(Response $response)
    {
        $moduleController = ucfirst(strtolower($response->getController())) . 'Controller';
        $controllerFile   = $response->getModulePath() . DS . 'src' . DS . 'Controller' . DS . $moduleController . '.php';

        if (!file_exists($controllerFile)) {
            throw new ResponseInvalidParameter('Response controller not found');
        }

        $controllerClass = ucfirst($response->getModule()) . '\\Controller\\' . $moduleController;
        $actionMethod    = strtolower($response->getAction()) . 'Action';

        if (INTERFACE_TYPE == 'http') {
            $viewPath  = $response->getModulePath() . DS . 'view' . DS . strtolower($response->getController());
            $viewModel = new ViewModel();
            $viewModel->setPath($viewPath);

            $controller = new $controllerClass($this->container, $viewModel);
        } else {
            $controller = new $controllerClass($this->container);
        }

        if (!method_exists($controller, $actionMethod)) {
            throw new ResponseInvalidParameter('Response action method not found');
        }

        if (!empty($response->getParams())) {
            return $controller->$actionMethod($response->getParams());
        }

        return $controller->$actionMethod();
    }
}