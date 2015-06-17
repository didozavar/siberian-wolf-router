<?php

namespace SiberianWolf\Router\Contract;

interface RouteInterface {

    public function getName();

    public function setName($name);

    public function setRoute($route);

    public function getRoute();

    public function getController();

    public function setController($controller);

    public function getAction();

    public function setAction($action);

    public function getParams();

    public function getParam($key);

    public function setParams(array $params);

    public function addParam($key, $value);
}