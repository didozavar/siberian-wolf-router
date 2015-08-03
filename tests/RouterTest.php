<?php

use \SiberianWolf\Router\Router;
use \SiberianWolf\Router\RouteCollection;
use \SiberianWolf\Router\RouteFactory;

class RouterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \SiberianWolf\Router\Router
     */
    private $router;

    private $routeFactory;

    public function setUp()
    {
        $routes = [
            'home' => [
                'method' => 'get',
                'uri' => '/',
                'handler' => 'Home\Controller\IndexController@index'
            ],
            'test' => [
                'method' => 'any',
                'uri' => '/test',
                'handler' => 'Home\Controller\IndexController@index'
            ],
            'user' => [
                'method' => 'get',
                'uri' => '/users',
                'handler' => 'UserController@list'
            ],
            'user-edit' => [
                'method' => 'get',
                'uri' => '/user/{user_id}',
                'handler' => 'UserController@edit'
            ],
            'user-add' => [
                'method' => 'get',
                'uri' => '/user',
                'handler' => 'IndexController@index'
            ],
            'user-delete' => [
                'method' => 'get',
                'uri' => '/user/delete/{user_id}',
                'handler' => 'IndexController@delete'
            ]
        ];

        $routeCollection = new RouteCollection();
        $this->routeFactory = new RouteFactory();

        foreach ($routes as $id => $data) {
            $routeCollection[$id] = $this->routeFactory->create($id, $data);
        }

        $this->router = new Router($routeCollection);
    }

    public function testRouterMatch()
    {
        $result = $this->router->match('get', '/');
        $this->assertEquals('home', $result->getId());

        $result = $this->router->match('get', '/user/5');
        $this->assertEquals('user-edit', $result->getId());
    }

    public function testGenerateURI()
    {
        $result = $this->router->createURI('user-edit', [['name' => 'user_id', 'value' => 1]]);
        $this->assertEquals('/user/1', $result);

        $result = $this->router->createURI('user-edit', [['name' => 'user_id', 'value' => 1]]);
        $this->assertEquals('/user/1', $result);
    }

}