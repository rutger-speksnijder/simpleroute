# Simple Route

[![Total Downloads](https://poser.pugx.org/rutger/simpleroute/downloads)](https://packagist.org/packages/rutger/simpleroute)
[![Latest Stable Version](https://poser.pugx.org/rutger/simpleroute/v/stable)](https://packagist.org/packages/rutger/simpleroute)
[![Build Status](https://travis-ci.org/rutger-speksnijder/simpleroute.svg?branch=master)](https://travis-ci.org/rutger-speksnijder/simpleroute)
[![Latest Unstable Version](https://poser.pugx.org/rutger/simpleroute/v/unstable)](https://packagist.org/packages/rutger/simpleroute)
[![License](https://poser.pugx.org/rutger/simpleroute/license)](https://packagist.org/packages/rutger/simpleroute)


A simple and easy to use router for PHP.

## Features

 - Easy to use
 - Lightweight
 - Supports all HTTP request methods (and it's very easy to add more)
 - Supports regex in routes

## Installation

Install using composer:

```sh
composer require rutger/simpleroute
```

## Usage

Make sure all requests point to a file in which you will handle the requests.

### Rewrite

With apache you can use the following htaccess lines for this:

```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*)$ index.php?l=$1 [L,QSA,NC]
```

This will point every request to index.php?l=REQUEST_URI. "l" can be something else, I just chose something short here.

### Creating the router

```php
// Composer autoloader
require 'vendor/autoload.php';

// Create the router
$router = new \SimpleRoute\Router(
    strtolower($_SERVER['REQUEST_METHOD']),
    isset($_GET['l']) ? $_GET['l'] : ''
);
```

### Adding routes

The router has a method called "add". It takes three arguments:
 - Route: The route to bind a callable to.
 - Callable: The callable to call when the route is requested.
 - Type: The request method for which to execute this route ("get", "post", "put", "patch", "options", "head", "delete" or "any").

There is a shortcut method for every request method.

Make sure to always add a default "/" route. This will then work as your "not found" route.
If you do not specify this route, the Router will throw an exception on execute.

You can also remove routes using the remove method.

```php
// Basic example, executes for all request methods.
$router->add('/example', function() {
    echo 'Example.';
});

// Not found route
$router->add('/', function() {
    echo 'Not found.';
});

// Get example
$router->get('/home', function() {
    echo 'Homepage';
});

// Head example
// Head requests should not return a body.
$router->head('/user/([0-9]+)', function($id) {
    // Check if the user exists
    if ($id == 123) {
        header('HTTP/1.1 200 OK');
    } else {
        header('HTTP/1.1 404 Not Found');
    }
});

// Example using a class with a method
class Controller {
    public function example() {
        echo 'Controller example';
    }
}
$controller = new Controller;
$router->add('/controller/example', array($controller, 'example'));
```

### Executing the router

Executing the router is done by calling the execute method.

```php
// Router creation, adding routes...

// Execute the router
$router->execute();
```

## Contact

Feel free to contact me at rutgerspeksnijder@hotmail.com if you have any questions.

## License

The MIT License (MIT)

Copyright (c) 2016 Rutger Speksnijder

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
