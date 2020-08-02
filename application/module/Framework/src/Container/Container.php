<?php
namespace Framework\Container;

use Framework\Container\Exception\ObjectNotFoundException;

class Container
{
    /**
     * @var array
     */
    public $registry = [];

    /**
     * @param $name
     * @return mixed
     * @throws ObjectNotFoundException
     */
    public function get($name)
    {
        if (!isset($this->registry[$name])) {
            throw new ObjectNotFoundException(
                sprintf('Object %s doesn\'t exists in the container registry', $name)
            );
        }

        return $this->registry[$name];
    }

    /**
     * @param $name
     * @param $object
     */
    public function set($name, $object)
    {
        $this->registry[$name] = $object;
    }
}