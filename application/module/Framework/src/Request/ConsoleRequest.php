<?php
namespace Framework\Request;

class ConsoleRequest
{
    /**
     * @return string
     */
    public function getCommandParams(): string
    {
        return $_SERVER['argv'][1] ?? '';
    }
}