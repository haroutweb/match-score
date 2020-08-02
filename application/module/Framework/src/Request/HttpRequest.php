<?php
namespace Framework\Request;

class HttpRequest
{
    /**
     * @var string
     */
    private $requestType;

    /**
     * @return bool
     */
    public function isPostRequest(): bool 
    {
        return $_SERVER['REQUEST_METHOD'] == 'POST';
    }

    /**
     * @return bool
     */
    public function isGetRequest(): bool 
    {
        return $_SERVER['REQUEST_METHOD'] == 'GET';
    }

    /**
     * @return bool
     */
    public function isDeleteRequest(): bool 
    {
        return $_SERVER['REQUEST_METHOD'] == 'DELETE';
    }

    /**
     * @return bool
     */
    public function isPutRequest(): bool 
    {
        return $_SERVER['REQUEST_METHOD'] == 'PUT';
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getRequestType(): string
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'PUT':
                $this->requestType = 'PUT';
                break;
            case 'POST':
                $this->requestType = 'POST';
                break;
            case 'GET':
                $this->requestType = 'GET';
                break;
            case 'DELETE':
                $this->requestType = 'DELETE';
                break;
            default:
                throw new \Exception('Invalid request type');
        }

        return $this->requestType;
    }

    /**
     * @return string
     */
    public function getUrlFromRequest(): string
    {
        return $_SERVER['REQUEST_URI'];
    }
}