<?php

function getHTTPMethod()
{
    return $_SERVER['REQUEST_METHOD'];
}
function getURI()
{
    return $_SERVER['REQUEST_URI'];
}
function getRoute()
{
    $uri = getURI();
    $route = substr($uri, 1);
    return $route;
}
function getParams()
{
    $route = getRoute();
    $params = explode('/', $route);
    return $params;
}
function getController()
{
    $params = getParams();
    $controller = $params[0];
    return $controller;
}