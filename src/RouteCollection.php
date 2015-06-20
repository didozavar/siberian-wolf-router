<?php
/**
 * Created by PhpStorm.
 * User: Dian
 * Date: 6/20/2015
 * Time: 8:52 PM
 */

namespace SiberianWolf\Router;


class RouteCollection implements \ArrayAccess, \IteratorAggregate   {

    protected $routes;

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($routes[$offset]);
    }

    /**
     * @param string $offset
     * @return RouteInterface
     */
    public function offsetGet($offset)
    {
        return $this->routes[$offset];
    }

    /**
     * @param string $offset
     * @param RouteInterface $value
     */
    public function offsetSet($offset, $value)
    {
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