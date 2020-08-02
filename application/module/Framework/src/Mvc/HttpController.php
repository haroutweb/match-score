<?php
namespace Framework\Mvc;

use Framework\Container\Container;

class HttpController
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var ViewModel
     */
    protected $viewModel;

    /**
     * @param Container $container
     * @param ViewModel $viewModel
     */
    public function __construct(Container $container, ViewModel $viewModel)
    {
        $this->container = $container;
        $this->viewModel = $viewModel;

        // add before action listener
        if (method_exists($this, 'preload')) {
            $this->preload();
        }
    }

    /**
     * @param $data
     * @return string
     */
    public function renderJson($data)
    {
        echo json_encode($data, 1);
        exit;
    }
}