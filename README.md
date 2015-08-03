# siberian-wolf-router

[![Build Status](https://travis-ci.org/didozavar/siberian-wolf-router.svg?branch=master)](https://travis-ci.org/didozavar/siberian-wolf-router)

Router library
Version: 0.5.1

How to use it:
```
$routes = [
            'home' => ['method' => 'get','uri' => '/','handler' => 'Home\Controller\IndexController@index']
];

$routes = new RouteCollection();

foreach($routes as $key => $value)
{
    $routes[$key] = RouteFactory::create($key, $value);
}

$router = new Router($routes);

//find or throw exception not found.
$router->match('home/test/user/12');
```