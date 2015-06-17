<?php

include '../../vendor/autoload.php';

class RouterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \SiberianWolf\Router\Router
     */
    private $router;

    public function setUp()
    {
        $routes = [
            'home' => ['method' => 'get', 'route' => '/',
                'handler' => 'Home\Controller\IndexController@index'],
            'test' => ['method' => 'any', 'route' => '/test',
                'handler' => 'Home\Controller\IndexController@index'],
            'user' => ['method' => 'get', 'route' => '/users',
                'handler' => 'UserController@list'],
            'user-edit' => ['method' => 'get', 'route' => '/user/{user_id}',
                'handler' => 'UserController@edit'],
            'user-add' => ['method' => 'get', 'route' => '/user',
                'handler' => 'IndexController@index'],
            'user-delete' => ['method' => 'get', 'route' => '/user/delete/{user_id}',
                'handler' => 'IndexController@delete']
        ];

        $this->router = new \SiberianWolf\Router\Router($routes);
    }

    public function testRouterMatch()
    {
        $result = $this->router->match('get', '/');
        $this->assertEquals('home', $result['name']);

        $result = $this->router->match('get', '/user/5');
        $this->assertEquals('user-edit', $result['name']);
    }

    public function testGenerateURI()
    {
        $result = $this->router->createURI('user-edit', [['name' => 'user_id', 'value' => 1]]);
        $this->assertEquals('/user/1', $result);

        $result = $this->router->createURI('user-edit', [['name' => 'user_id', 'value' => 1]]);
        $this->assertEquals('/user/1', $result);
    }

    /**
     * @expectedException \SiberianWolf\Router\Exceptions\MissingRouteDataException
     */
    public function testValidRoutesThrowExceptions()
    {
        $routes = [
            'user-delete' => ['route' => '/', 'handler' => 'IndexController@index']
        ];

        $this->router->setRoutes($routes);
    }
}