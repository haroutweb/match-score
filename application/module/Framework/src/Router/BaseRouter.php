<?php
namespace Framework\Router;

use Framework\Response\Response;

class BaseRouter
{
    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var array
     */
    private $modulePaths = [];

    /**
     * @param array $modulePaths
     */
    public function locateRoutes(array $modulePaths)
    {
        $this->modulePaths = $modulePaths;

        foreach ($modulePaths as $name => $path) {
            $routerFile = $path . DS . 'config' . DS . $this->fileName;

            if (!file_exists($routerFile)) {
                continue;
            }

            $route        = include($routerFile);
            $this->routes = array_replace($this->routes, $route);
        }
    }

    /**
     * @param $url
     * @return Response
     */
    public function generateResponse($url): Response
    {
        $isMatch = false;
        $params  = [];

        foreach ($this->routes as $rule => $settings) {
            $settings['matches'] = $settings['matches'] ?? [];

            if (preg_match($rule, $url, $matches) &&
                (
                    isset($this->modulePaths[$settings['module']]) ||
                    isset($this->modulePaths[ucfirst($settings['module'])])
                )
            ) {
                $params['modulePath'] = $this->modulePaths[$settings['module']] ?? $this->modulePaths[ucfirst($settings['module'])];
                $params['module']     = $settings['module'];
                $params['controller'] = $settings['controller'];
                $params['action']     = $settings['action'];

                array_shift($matches);

                foreach ($settings['matches'] as $key => $varName) {
                    if (empty($varName)) {
                        continue;
                    }

                    if (isset($matches[$key])){
                        $params[$varName] = $matches[$key];
                    }
                }

                $isMatch = true;
                break;
            }
        }

        if (!$isMatch) {
            return $this->matchInvalidRequest();
        }

        return $this->getResponse($params);
    }

    /**
     * @param $urlParams
     * @return Response
     */
    private function getResponse($urlParams): Response
    {
        $response = new Response();

        if (isset($urlParams['module'])) {
            $response->setModule($urlParams['module']);
            unset($urlParams['module']);
        }

        if (isset($urlParams['modulePath'])) {
            $response->setModulePath($urlParams['modulePath']);
            unset($urlParams['modulePath']);
        }

        if (isset($urlParams['controller'])) {
            $response->setController($urlParams['controller']);
            unset($urlParams['controller']);
        }

        if (isset($urlParams['action'])) {
            $response->setAction($urlParams['action']);
            unset($urlParams['action']);
        }

        $params = [];
        foreach($urlParams as $name => $value) {
            $params[$name] = $value;
        }

        $response->setParams($params);
        
        return $response;
    }
}