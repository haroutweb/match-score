<?php
namespace Framework\Mvc;

use Framework\Container\Container;

class ConsoleController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;

        // add before action listener
        if (method_exists($this, 'preload')) {
            $this->preload();
        }
    }

    /**
     * @param $message
     */
    protected function error($message)
    {
        echo "\e[0;31m" . $message . "\e[0m\n\r";
        exit;
    }

    /**
     * @param $message
     */
    protected function print($message)
    {
        echo "\e[0;32m" . $message . "\e[0m\n\r";
        exit;
    }
}