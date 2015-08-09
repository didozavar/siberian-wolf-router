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
            [
                'name' => 'home',
                'method' => 'get',
                'uri' => '/',
                'handler' => 'Home\Controller\IndexController@index',
            ],
            [
                'name' => 'test',
                'method' => 'any',
                'uri' => '/test',
                'handler' => 'Home\Controller\IndexController@index',
            ],
            [
                'name' => 'user',
                'method' => 'get',
                'uri' => '/users',
                'handler' => 'UserController@list',
            ],
            [
                'name' => 'user-edit',
                'method' => 'get',
                'uri' => '/user/{user_id}',
                'handler' => 'UserController@edit',
            ],
            [
                'name' => 'user-edit-long',
                'method' => 'get',
                'uri' => '/user/{user_id}/pic/{user_pic_id}',
                'handler' => 'UserController@editLong',
            ],
            [
                'name' => 'user-add',
                'method' => 'get',
                'uri' => '/user',
                'handler' => 'IndexController@index',
            ],
            [
                'name' => 'user-delete',
                'method' => 'get',
                'uri' => '/user/delete/{user_id}',
                'handler' => 'IndexController@delete',
            ],
        ];

        $routeCollection = new RouteCollection();
        $this->routeFactory = new RouteFactory();

        foreach ($routes as $data) {
            $routeCollection[$data['name']] = $this->routeFactory->create($data);
        }

        $this->router = new Router($routeCollection);
    }

    public function testRouterMatch()
    {
        $result = $this->router->match('get', '/');
        $this->assertEquals('home', $result->getName());

        $result = $this->router->match('get', '/user/5');
        $this->assertEquals('user-edit', $result->getName());
    }

    public function testGenerateURI()
    {
        //user-edit-long

        $result = $this->router->createURI('user-edit', ['user_id' => 1]);
        $this->assertEquals('/user/1', $result);

        $result = $this->router->createURI('user-edit', ['user_id' => 1]);
        $this->assertEquals('/user/1', $result);

        $result = $this->router->createURI('user-edit-long', ['user_id' => 1, 'user_pic_id' => 2]);
        $this->assertEquals('/user/1/pic/2', $result);
    }
}
