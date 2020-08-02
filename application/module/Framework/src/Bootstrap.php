<?php
namespace Framework;

use Framework\Dispatcher\ConsoleDispatcher;
use Framework\Mvc\BaseApplication;
use Framework\Mvc\ConsoleApplication;
use Framework\Container\Container;
use Framework\ModuleManager\ModuleManager;
use Framework\Mvc\HttpApplication;
use Framework\Request\ConsoleRequest;
use Framework\Request\HttpRequest;
use Framework\Router\ConsoleRouter;
use Framework\Router\HttpRouter;
use Framework\Dispatcher\Dispatcher;

class Bootstrap
{
    /**
     * @param array $config
     * @return BaseApplication
     * @throws \Exception
     */
    public static function init(array $config): BaseApplication
    {
        if (!isset($config['modules'])) {
            throw new \Exception('Application haven\'t defined modules');
        }

        $moduleManager = new ModuleManager($config['modules']);

        $container = new Container();
        $container->set('applicationConfig', $config['application'] ?? []);
        $container->set('modulePaths', $moduleManager->getModulePaths());
        $container->set('dispatcher', new Dispatcher());

        if (self::isCommandLineInterface()) {
            $container->set('router', new ConsoleRouter());
            $container->set('request', new ConsoleRequest());
            $application = new ConsoleApplication($container);
        } else {
            $container->set('router', new HttpRouter());
            $container->set('request', new HttpRequest());
            $application = new HttpApplication($container);
        }

        return $application;
    }

    /**
     * @return bool
     */
    private static function isCommandLineInterface(): bool
    {
        return (INTERFACE_TYPE === 'cli');
    }
}