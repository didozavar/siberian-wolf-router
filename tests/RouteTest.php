<?php

use SiberianWolf\Router\Exception\InvalidRouteNameException;
use \SiberianWolf\Router\Route;

class RouteTest extends \PHPUnit_Framework_TestCase
{

    public function testInitializeRoute()
    {
        $route = new Route("test", "test-url", "post", "controller", "action");
        $this->assertInstanceOf("SiberianWolf\\Router\\Route", $route);
    }

    public function testRouteInvalidNameOnConstructor()
    {
        try {
            $route = new Route("", "test-url", "post", "controller", "action");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteNameException", $e);
        }
    }

    public function testRouteInvalidNameOnSetter()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        try {
            $route->setName("");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteNameException", $e);
        }
    }

    public function testGetRouteNameFromConstructor()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");

        $this->assertEquals("name", $route->getName());
    }

    public function testGetRouteNameFromSetter()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->setName("name2");
        $this->assertEquals("name2", $route->getName());
    }

    public function testRouteInvalidUriPatternOnConstructor()
    {
        try {
            $route = new Route("name", "", "post", "controller", "action");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteUriPatternException", $e);
        }
    }

    public function testRouteInvalidUriPatternOnSetter()
    {
        $route = new Route("name", "fdsa", "post", "controller", "action");
        try {
            $route->setUriPattern("");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteUriPatternException", $e);
        }
    }

    public function testGetRouteUriPatternFromConstructor()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");

        $this->assertEquals("test-url", $route->getUriPattern());
    }

    public function testGetRouteUriPatternFromSetter()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->setUriPattern("test-url2");
        $this->assertEquals("test-url2", $route->getUriPattern());
    }

    public function testRouteInvalidMethodOnConstructor()
    {
        try {
            $route = new Route("name", "uripattern", "post1", "controller", "action");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteMethodException", $e);
        }
    }

    public function testRouteInvalidMethodOnSetter()
    {
        $route = new Route("valid", "uripattern", "post", "controller", "action");
        try {
            $route->setMethod("get1");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteMethodException", $e);
        }
    }

    public function testGetRouteMethodFromConstructor()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");

        $this->assertEquals("post", $route->getMethod());
    }

    public function testGetRouteMethodFromSetter()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->setMethod("get");
        $this->assertEquals("get", $route->getMethod());
    }

    public function testRouteInvalidControllerOnConstructor()
{
    try {
        $route = new Route("name", "uripattern", "post", "", "action");
    } catch (\Exception $e) {
        $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteControllerException", $e);
    }
}

    public function testRouteInvalidControllerOnSetter()
    {
        $route = new Route("valid", "uripattern", "post", "controller", "action");
        try {
            $route->setController("");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteControllerException", $e);
        }
    }

    public function testGetRouteControllerFromConstructor()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");

        $this->assertEquals("controller", $route->getController());
    }

    public function testGetRouteControllerFromSetter()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->setController("controller2");
        $this->assertEquals("controller2", $route->getController());
    }

    public function testRouteInvalidActionOnConstructor()
    {
        try {
            $route = new Route("name", "uripattern", "post", "controller", "");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteActionException", $e);
        }
    }

    public function testRouteInvalidActionOnSetter()
    {
        $route = new Route("valid", "uripattern", "post", "controller", "action");
        try {
            $route->setAction("");
        } catch (\Exception $e) {
            $this->assertInstanceOf("SiberianWolf\\Router\\Exception\\InvalidRouteActionException", $e);
        }
    }

    public function testGetRouteActionFromConstructor()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");

        $this->assertEquals("action", $route->getAction());
    }

    public function testGetRouteActionFromSetter()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->setAction("action2");
        $this->assertEquals("action2", $route->getAction());
    }

    public function testSetParams()
    {
        $route = new Route("name", "test-url", "post", "controller", "action",
            ['id' => 15, 'test' => 'red']);

        $this->assertEquals(['id' => 15, 'test' => 'red'], $route->getParams());
    }

    public function testAddParam()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->addParam('key', 'value');
        $this->assertEquals('value', $route->getParam('key'));
    }

    public function testRemoveParam()
    {
        $route = new Route("name", "test-url", "post", "controller", "action");
        $route->addParam('key', 'value');
        $route->removeParam('key');
        $this->assertNull($route->getParam('key'));
    }

}
