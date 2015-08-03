<?php

namespace SiberianWolf\Router;

use \SiberianWolf\Router\Exception\RouteNotFoundException;

/**
 * Router service that match http request from predefined routers config to controller and actions
 * Class Router
 * @package SiberianWolf\Router
 */
class Router
{
    /**
     * @var RouteCollection
     */
    private $routeCollection;

    /**
     * @param RouteCollection $routeCollection
     */
    public function __construct(RouteCollection $routeCollection)
    {
        $this->routeCollection = $routeCollection;
    }

    /**
     * @param RouteCollection $routes
     */
    public function setRoutes(RouteCollection $routes)
    {
        $this->routeCollection = $routes;
    }

    /**
     * @return RouteCollection
     */
    public function getRoutes()
    {
        return $this->routeCollection;
    }

    /**
     * @param string $method
     * @param string $name
     * @return RouteInterface
     * @throws RouteNotFoundException
     */
    public function match($method, $name)
    {
        $matchedRoute = null;

        foreach ($this->routeCollection as $route) {
            if ($route->getMethod() != $method && $route->getMethod() != 'any') {
                continue;
            }

            $newMatch = '|^' . preg_replace('|{.+?}|', '[\d\w-]+?', $route->getUriPattern()) . 'end$|';
            $result = preg_match($newMatch, $name . 'end');

            if ($result == 1) {
                $matchedRoute = $route;
                break;
            }
        }

        if (is_null($matchedRoute)) {
            throw new RouteNotFoundException('Route with name $name is not found');
        }

        $params = $this->getRouteParams($matchedRoute->getUriPattern(), $name);
        $matchedRoute->setParams($params);

        return $matchedRoute;
    }


    /**
     * @param string $routeUriPattern
     * @param string $uri
     * @return array
     */
    private function getRouteParams($routeUriPattern, $uri)
    {
        $routeSegment = explode('/', $routeUriPattern);
        $uriSegment = explode('/', $uri);
        $length = count($routeSegment);
        $params = [];

        for ($i = 0; $i < $length; $i++) {
            if (isset($routeSegment[$i][0]) && $routeSegment[$i][0] == '{') {
                $key = trim($routeSegment[$i], "{}");
                $params[$key] = $uriSegment[$i];
            }
        }

        return $params;
    }

    /**
     * @param string $name
     * @param array $params
     * @throws RouteNotFoundException
     * @return string
     */
    public function createURI($name, array $params)
    {
        $route = $this->routeCollection[$name];

        $generatedRoute = $route->getUriPattern();
        foreach ($params as $param) {
            $generatedRoute = str_replace('{' . $param['name'] . '}', $param['value'], $generatedRoute);
        }
        return $generatedRoute;
    }
}
