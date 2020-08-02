<?php
namespace Framework\Router;

class HttpRouter extends BaseRouter
{
    /**
     * @var string
     */
    protected $fileName = 'http.router.php';

    /**
     * @return \Framework\Response\Response
     */
    protected function matchInvalidRequest()
    {
        return $this->generateResponse('/404');
    }
}