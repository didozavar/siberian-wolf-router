<?php

namespace SiberianWolf\Router;

use SiberianWolf\Router\Exception\InvalidRouteMethodException;
use SiberianWolf\Router\Exception\InvalidRouteIdException;
use SiberianWolf\Router\Exception\InvalidRouteControllerException;

/**
 * Just a value object that holds name, route, controller, action and params from current route
 * Class Route
 * @package SiberianWolf\Router
 */
interface RouteInterface
{
    /**
     * @return string
     */
    public function getId();

    /**
     * @param string $id
     * @throws InvalidRouteIdException
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getUriPattern();

    /**
     * @param string $uriPattern
     */
    public function setUriPattern($uriPattern);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @param $method
     * @throws InvalidRouteMethodException
     */
    public function setMethod($method);

    /**
     * @return string
     */
    public function getController();

    /**
     * @param $controller
     * @throws InvalidRouteControllerException
     */
    public function setController($controller);

    /**
     * @return string
     */
    public function getAction();

    public function setAction($action);

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param string $key
     * @return mixed
     */
    public function getParam($key);

    /**
     * @param array $params
     */
    public function setParams(array $params);

    /**
     * @param string $name
     * @param string $value
     */
    public function addParam($name, $value);

    /**
     * @param string $name
     */
    public function removeParam($name);
}
