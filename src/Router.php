<?php

namespace SiberianWolf\Router;

use \SiberianWolf\Router\Contract\RouteInterface;
use \SiberianWolf\Router\Exception\RouteNotFoundException;
use \SiberianWolf\Router\Exception\InvalidMethodException;
use \SiberianWolf\Router\Exception\InvalidHandlerException;

/**
 * Router service that match http request from predefined routers config and generate request
 * Class Router
 * @package SiberianWolf\Router
 */
class Router
{

    /**
     * @var array
     */
    private $routes = [];

    /**
     * @var array
     */
    private $requiredRouteFields = ['route', 'handler', 'method'];

    /**
     * @var array
     */
    private $allowedMethods = ['get', 'post', 'put', 'delete', 'any'];

    /**
     * @param array $routes
     */
    public function __construct(array $routes = [])
    {
        $this->setRoutes($routes);
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param array $routes
     */
    public function setRoutes(array $routes)
    {
        $this->clearRoutes();

        foreach ($routes as $name => $route) {
            $this->addRoute($name, $route);
        }
    }

    /**
     * Clear routes
     */
    public function clearRoutes()
    {
        $this->routes = [];
    }

    /**
     * @param string $name
     * @param array $route
     */
    public function addRoute($name, $route)
    {
        $route = $this->normalizeRoute($route);

        $this->validateRoute($name, $route);
        $this->routes[$name] = $route;
    }

    /**
     * @param array $route
     * @return array
     */
    private function normalizeRoute(array $route)
    {
        foreach ($this->requiredRouteFields as $field) {
            if (!isset($route[$field])) {
                $route[$field] = '';
            }

            $route[$field] = trim($route[$field]);
        }

        return $route;
    }

    /**
     * @param string $name
     * @return array
     * @throws RouteNotFoundException
     */
    public function getRoute($name)
    {
        if (!$this->hasRoute($name)) {
            throw new RouteNotFoundException('Route with name $name is not found');
        }

        return $this->routes[$name];
    }


    /**
     * @param string $name
     * @return bool
     */
    public function hasRoute($name)
    {
        return isset($this->routes[$name]);
    }

    /**
     * @param string $name
     * @param array $route
     */
    private function validateRoute($name, array $route)
    {
        foreach ($this->requiredRouteFields as $field) {
            $this->validateRouteField($name, $route, $field);
        }
    }

    /**
     * @param string $name
     * @param array $route
     * @param $field
     */
    private function validateRouteField($name, array $route, $field)
    {
        if ($field == 'handler') {
            $this->validateRouteHandler($name, $route['handler']);
        } else if ($field == 'method') {
            $this->validateRouteMethod($name, $route['method']);
        } else if ($field == 'route') {
            $this->validateRouteRoute($name, $route['route']);
        }
    }

    private function validateRouteRoute($name, $route)
    {
        if(strlen(trim($route)) <= 0)
        {
            throw new \Exception("Route '$name': Route with name '$route'', is not allowed");
        }
    }

    /**
     * @param string $name
     * @param string $method
     * @throws InvalidMethodException
     */
    public function validateRouteMethod($name, $method)
    {
        if (!in_array($method, $this->allowedMethods)) {
            throw new InvalidMethodException("Route '$name': Method with name '$method'', is not allowed");
        }
    }

    /**
     * @param string $name
     * @param string $handler
     * @throws InvalidHandlerException
     */
    private function validateRouteHandler($name, $handler)
    {
        if (strlen($handler) < 3) {
            throw new InvalidHandlerException("Route '$name': Handler must be at least 3 symbols");
        }

        if (strpos($handler, '@') === false) {
            throw new InvalidHandlerException("Route '$name': Handler must have @ sign");
        }

        if ($handler[0] === '@' || $handler[strlen($handler) - 1] === '@') {
            throw new InvalidHandlerException("Route '$name': Handler must not have @ sign in the beginning/end");
        }
    }

    /**
     * @param string $method
     * @param string $name
     * @return array
     * @throws RouteNotFoundException
     */
    public function match($method, $name)
    {
        $matchedName = null;

        foreach ($this->getRoutes() as $routeName => $route) {

            if ($route['method'] != $method && $route['method'] != 'any') {
                continue;
            }

            $newMatch = '|^' . preg_replace('|{.+?}|', '[\d\w-]+?', $route['route']) . '//$|';
            $result = preg_match($newMatch, $name . '//');

            if ($result == 1) {
                $matchedName = $routeName;
                break;
            }

        }

        if (is_null($matchedName)) {
            throw new RouteNotFoundException('Route with name $name is not found');
        }

        return $this->generateRoute($matchedName, $name);
    }

    /**
     * @param string $name
     * @param string $uri
     * @return RouteInterface
     */
    private function generateRoute($name, $uri)
    {
        $generatedRoute = [];
        $route = $this->getRoute($name);
        $generatedRoute['name'] = $name;
        $generatedRoute['route'] = $route['route'];

        $handlerPart = explode('@', $route['handler']);
        $generatedRoute['controller'] = $handlerPart[0];
        $generatedRoute['action'] = $handlerPart[1];

        $routeSegment = explode('/', $route['route']);
        $uriSegment = explode('/', $uri);
        $length = count($routeSegment);

        for ($i = 0; $i < $length; $i++) {
            if (isset($routeSegment[$i][0]) && $routeSegment[$i][0] == '{') {
                $key = trim($routeSegment[$i], "{}");
                $generatedRoute['params'][$key] = $uriSegment[$i];
            }
        }

        return $generatedRoute;
    }

    /**
     * @param string $name
     * @param array $params
     * @throws RouteNotFoundException
     * @return string
     */
    public function createURI($name, array $params)
    {
        $route = $this->getRoute($name);

        $generatedRoute = $route['route'];
        foreach ($params as $param) {
            $generatedRoute = str_replace('{' . $param['name'] . '}', $param['value'], $generatedRoute);
        }
        return $generatedRoute;

    }
}