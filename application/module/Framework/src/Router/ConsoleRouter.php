<?php
namespace Framework\Router;

use Framework\Request\Exception\InvalidRequestException;

class ConsoleRouter extends BaseRouter
{
    /**
     * @var string
     */
    protected $fileName = 'cli.router.php';

    /**
     * @throws InvalidRequestException
     */
    protected function matchInvalidRequest()
    {
        return $this->generateResponse('help');
    }
}