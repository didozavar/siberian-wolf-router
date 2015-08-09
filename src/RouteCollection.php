<?php

namespace SiberianWolf\Router;

/**
 * Ensuring that every route in collection is from type Route
 * Class RouteCollection.
 */
class RouteCollection implements \ArrayAccess, \IteratorAggregate
{
    /**
     * @var array
     */
    protected $routes;

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($routes[$offset]);
    }

    /**
     * @param string $offset
     *
     * @return RouteInterface
     */
    public function offsetGet($offset)
    {
        return $this->routes[$offset];
    }

    /**
     * @param string         $offset
     * @param RouteInterface $value
     */
    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Route) {
            throw new \InvalidArgumentException('Route collection accept only SiberianWolf\\Router\\Route object');
        }

        $this->routes[$offset] = $value;
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->routes[$offset]);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->routes);
    }
}
