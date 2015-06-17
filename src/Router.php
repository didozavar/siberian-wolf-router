<?php

namespace SiberianWolf\Router;

use \SiberianWolf\Router\Contract\RouteInterface;
use \SiberianWolf\Router\Exceptions\RouteNotFound;

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
    //private $allowedMethods = ['get', 'post', 'put', 'delete', 'any'];

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
     * @throws Exceptions\MissingRouteDataException
     */
    public function addRoute($name, $route)
    {
        $this->validateRoute($name, $route);
        $this->routes[$name] = $route;
    }

    /**
     * @param string $name
     * @return array
     * @throws RouteNotFound
     */
    public function getRoute($name)
    {
        if (!$this->hasRoute($name)) {
            throw new RouteNotFound('Route with name $name is not found');
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
     * @throws Exceptions\MissingRouteDataException
     */
    private function validateRoute($name, $route)
    {
        foreach ($this->requiredRouteFields as $field) {
            if (!isset($route[$field])) {
                throw new Exceptions\MissingRouteDataException("Route $name dont have a ($field) field");
            }
            //$this->validateRouteField($route, $field);
        }
    }

    /**
     * @param string $method
     * @param string $name
     * @return array
     * @throws Exceptions\MissingRouteDataException
     * @throws RouteNotFound
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
            throw new RouteNotFound('Route with name $name is not found');
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
     * @throws RouteNotFound
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