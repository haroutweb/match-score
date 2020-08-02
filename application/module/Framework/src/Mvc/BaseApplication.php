<?php
namespace Framework\Mvc;

use Framework\Container\Container;

abstract class BaseApplication
{
    /**
     * @var Container
     */
    protected $container;
    
    /**
     * @return mixed
     */
    abstract public function run();

    /**
     * BaseApplication constructor.
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}