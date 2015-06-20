<?php

namespace SiberianWolf\Router;

use SiberianWolf\Router\Exception\InvalidRouteIdException;
use SiberianWolf\Router\Exception\InvalidRouteMethodException;
use SiberianWolf\Router\Exception\InvalidRouteActionException;
use SiberianWolf\Router\Exception\InvalidRouteControllerException;

/**
 * Just a value object that holds name, route, controller, action and params from current route
 * Class Route
 * @package SiberianWolf\Router
 */
class Route implements RouteInterface
{

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $uriPattern;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $allowedMethods = ['get', 'post', 'put', 'delete', 'any'];

    /**
     * @var string
     */
    private $controller;

    /**
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $params = array();

    public function __construct($id, $uriPattern, $method, $controller, $action, array $params = [])
    {
        $this->setId($id);
        $this->setUriPattern($uriPattern);
        $this->setMethod($method);
        $this->setController($controller);
        $this->setAction($action);
        $this->setParams($params);
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     * @throws InvalidRouteIdException
     */
    public function setId($id)
    {
        $id = trim($id);
        if (strlen($id) <= 0) {
            throw new InvalidRouteIdException("Invalid route id: $id");
        }

        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getUriPattern()
    {
        return $this->uriPattern;
    }

    /**
     * @param string $uriPattern
     */
    public function setUriPattern($uriPattern)
    {
        // TODO: implement validation someday
        $this->uriPattern = $uriPattern;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }


    /**
     * @param $method
     * @throws InvalidRouteMethodException
     */
    public function setMethod($method)
    {
        if (!in_array($method, $this->allowedMethods)) {
            throw new InvalidRouteMethodException("Invalid route method: $method");
        }

        $this->method = $method;
    }


    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }


    /**
     * @param $controller
     * @throws InvalidRouteControllerException
     */
    public function setController($controller)
    {
        if (strlen($controller) <= 0) {
            throw new InvalidRouteControllerException();
        }

        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        if (strlen($action) <= 0) {
            throw new InvalidRouteActionException();
        }

        $this->action = $action;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getParam($key)
    {
        return (isset($this->params[$key])) ? $this->params[$key] : null;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params)
    {
        foreach ($params as $name => $value) {
            $this->addParam($name, $value);
        }
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;
    }

    /**
     * @param string $name
     */
    public function removeParam($name)
    {
        if (isset($this->params[$name])) {
            unset($this->params[$name]);
        }
    }
}