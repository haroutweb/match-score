<?php
namespace Framework\Mvc;

use Framework\Dispatcher\ConsoleDispatcher;
use Framework\Dispatcher\Dispatcher;
use Framework\Request\ConsoleRequest;
use Framework\Router\ConsoleRouter;

class ConsoleApplication extends BaseApplication
{
    /**
     * @return mixed
     */
    public function run()
    {
        /**
         * @var ConsoleRouter  $router
         * @var ConsoleRequest $request
         * @var Dispatcher     $dispatcher
         */
        $router     = $this->container->get('router');
        $request    = $this->container->get('request');
        $dispatcher = $this->container->get('dispatcher');

        $router->locateRoutes($this->container->get('modulePaths'));

        $response = $router->generateResponse($request->getCommandParams());

        $this->container->set('response', $response);
        $dispatcher->dispatch($this->container);
    }
}