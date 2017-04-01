<?php
// Composer autoloader
require 'vendor/autoload.php';

// Create the router
$router = new \SimpleRoute\Router($_SERVER['REQUEST_URI']);

// Basic example, executes for all request methods.
$router->add(
    '/example',
    function()
    {
        echo 'Example.';
    }
);

// Not found route
$router->add(
    '/',
    function()
    {
        echo 'Not found.';
    }
);

// Get example
$router->get(
    '/home',
    function()
    {
        echo 'Homepage';
    }
);

// Head example
// Head requests shouldn't return a body.
$router->head(
    '/user/([0-9]+)',
    function($id)
    {
        // Check if the user exists
        if ($id == 123) {
            header('HTTP/1.1 200 OK');
        } else {
            header('HTTP/1.1 404 Not Found');
        }
    }
);

// Example using a class with a method
class Controller
{
    public function example()
    {
        echo 'Controller example';
    }
}
$controller = new Controller;
$router->add('/controller/example', [$controller, 'example']);

// Execute the router
$router->execute();
