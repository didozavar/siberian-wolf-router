<?php
/**
 * Created by PhpStorm.
 * User: Dian
 * Date: 6/21/2015
 * Time: 12:32 AM
 */

namespace SiberianWolf\Router;


class RouteFactory
{

    public function create($id, array $data)
    {
        $handlerParams = explode('@', $data['handler']);
        $controller = $handlerParams[0];
        $action = $handlerParams[1];
        return new Route($id, $data['uri'], $data['method'], $controller, $action);
    }
}