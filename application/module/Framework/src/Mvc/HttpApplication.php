<?php
namespace Framework\Mvc;

use Framework\Dispatcher\Dispatcher;
use Framework\Request\HttpRequest;
use Framework\Router\HttpRouter;

class HttpApplication extends BaseApplication
{
    /**
     * @return mixed
     */
    public function run()
    {
        /**
         * @var HttpRouter  $router
         * @var HttpRequest $request
         * @var Dispatcher  $dispatcher
         */
        $router     = $this->container->get('router');
        $request    = $this->container->get('request');
        $dispatcher = $this->container->get('dispatcher');

        $router->locateRoutes($this->container->get('modulePaths'));

        $response = $router->generateResponse($request->getUrlFromRequest());

        $this->container->set('response', $response);
        $dispatcher->dispatch($this->container);
    }
}