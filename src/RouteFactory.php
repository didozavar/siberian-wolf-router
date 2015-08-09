<?php

namespace SiberianWolf\Router;

/**
 * Class RouteFactory.
 */
class RouteFactory
{
    /**
     * @param array $data
     *
     * @return Route
     */
    public function create($data)
    {
        $handlerParams = explode('@', $data['handler']);
        $controller = isset($handlerParams[0]) ? $handlerParams[0] : '';
        $action = isset($handlerParams[1]) ? $handlerParams[1] : '';

        return new Route($data['name'], $data['uri'], $data['method'], $controller, $action);
    }
}
