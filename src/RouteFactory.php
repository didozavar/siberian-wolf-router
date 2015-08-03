<?php

namespace SiberianWolf\Router;

/**
 *
 * Class RouteFactory
 * @package SiberianWolf\Router
 */
class RouteFactory
{
    /**
     * @param $id
     * @param array $data
     * @return Route
     */
    public function create($id, array $data)
    {
        $handlerParams = explode('@', $data['handler']);
        $controller = $handlerParams[0];
        $action = $handlerParams[1];
        return new Route($id, $data['uri'], $data['method'], $controller, $action);
    }
}
