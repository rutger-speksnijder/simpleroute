<?php
/*
    The MIT License (MIT)

    Copyright (c) 2017 Rutger Speksnijder

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
*/
namespace SimpleRoute;

/**
 * Router class for routing routes to callables.
 *
 * @author Rutger Speksnijder
 * @since SimpleRoute 1.0.0
 * @license https://github.com/rutger-speksnijder/simpleroute/blob/master/LICENSE
 */
class Router
{
    /**
     * The array with routes and their callables.
     * @var array.
     */
    private $routes = [
        'any' => [],
        'get' => [],
        'post' => [],
        'put' => [],
        'delete' => [],
        'head' => [],
        'options' => [],
        'patch' => [],
    ];

    /**
     * The request method.
     * @var string.
     */
    private $method = 'get';

    /**
     * The request url.
     * @var string.
     */
    private $url;

    /**
     * Constructs a new instance of the Router object.
     *
     * @param string $url The request url.
     */
    public function __construct($url = '')
    {
        // Set the url
        $this->url = $url;

        // Check if the url ends with a slash
        if ($this->url && substr($this->url, -1) !== '/') {
            $this->url .= '/';
        }

        // Set the request method
        $this->method = 'get';

        // Check if the request method is set in the headers
        if (isset($_SERVER['REQUEST_METHOD'])) {
            $this->method = strtolower($_SERVER['REQUEST_METHOD']);

            // Check if an override method is set
            if ($this->method == 'post' && isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
                $this->method = strtolower($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
            } elseif ($this->method == 'post' && isset($_SERVER['HTTP_X_HTTP_METHOD'])) {
                $this->method = strtolower($_SERVER['HTTP_X_HTTP_METHOD']);
            }
        }
    }

    /**
     * Sets the method.
     *
     * @param string $method The method.
     *
     * @return $this The current object.
     */
    public function setMethod($method)
    {
        $this->method = strtolower($method);
        return $this;
    }

    /**
     * Gets the request method.
     *
     * @return string The request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Sets the request url.
     *
     * @param string $url The url.
     *
     * @return $this The current object.
     */
    public function setUrl($url)
    {
        $this->url = $url;

        // Check if the url ends with a slash
        if ($this->url && substr($this->url, -1) !== '/') {
            $this->url .= '/';
        }

        return $this;
    }

    /**
     * Gets the request url.
     *
     * @return string The url.
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Adds a route to the router.
     *
     * @param string $route The route.
     * @param callable $callable The method to execute when this route gets called.
     * @param string $type The request type to bind this route to.
     *
     * @return $this The current object.
     */
    public function add($route, $callable, $type = 'any')
    {
        // Check if the route ends with a forward slash
        if ($route && substr($route, -1) !== '/') {
            $route .= '/';
        }

        // Add the route
        $this->routes[strtolower($type)][$route] = $callable;
        return $this;
    }

    /**
     * Adds a route to the router using type "get".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function get($route, $callable)
    {
        return $this->add($route, $callable, 'get');
    }

    /**
     * Adds a route to the router using type "post".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function post($route, $callable)
    {
        return $this->add($route, $callable, 'post');
    }

    /**
     * Adds a route to the router using type "put".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function put($route, $callable)
    {
        return $this->add($route, $callable, 'put');
    }

    /**
     * Adds a route to the router using type "delete".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function delete($route, $callable)
    {
        return $this->add($route, $callable, 'delete');
    }

    /**
     * Adds a route to the router using type "head".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function head($route, $callable)
    {
        return $this->add($route, $callable, 'head');
    }

    /**
     * Adds a route to the router using type "options".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function options($route, $callable)
    {
        return $this->add($route, $callable, 'options');
    }

    /**
     * Adds a route to the router using type "patch".
     *
     * @param @see \SimpleRoute\Router::add.
     *
     * @return @see \SimpleRoute\Router::add.
     */
    public function patch($route, $callable)
    {
        return $this->add($route, $callable, 'patch');
    }

    /**
     * Removes a route.
     *
     * @param string $route The route to remove.
     * @param string $type The request type.
     *
     * @return \SimpleRoute\Router The current object.
     */
    public function remove($route, $type = 'any')
    {
        if (isset($this->routes[$type][$route])) {
            unset($this->routes[$type][$route]);
        }
        return $this;
    }

    /**
     * Returns an array of methods defined for a specific route.
     * Useful for an "OPTIONS" request.
     *
     * @param string $route The route to get the methods from.
     *
     * @return array An array with methods.
     */
    public function getMethodsByRoute($route)
    {
        // Check if a route was provided
        if (!$route) {
            return [];
        }

        // Loop through our routes
        $methods = [];
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $availableRoute => $callable) {
                // Check if the available route is the same as the specified route
                if ($availableRoute === $route) {
                    $methods[] = $method;
                    continue 2;
                }

                // Check if the route matches
                $regex = '/^' . str_replace('/', '\/', $availableRoute) . '/Uim';
                if (preg_match($regex, $route, $matches) === 1 && $matches[0] === $route) {
                    $methods[] = $method;
                    continue 2;
                }
            }
        }

        // Return the methods array
        return $methods;
    }

    /**
     * Executes the router based on the url.
     *
     * @throws Exception Throws an exception if no location was set and no default route was found.
     *
     * @return mixed The result of the callable method.
     */
    public function execute()
    {
        // Check if we have a url
        if (!$this->url || trim($this->url) == '') {
            $this->url = '/';
        }

        // Make sure the url starts with a slash
        if (substr($this->url, 0, 1) !== '/') {
            $this->url = '/' . $this->url;
        }

        // Check if the absolute route exists in our routes array
        // - and if that route has the same type of request as the current request.
        if (isset($this->routes[$this->method][$this->url])) {
            return call_user_func_array($this->routes[$this->method][$this->url], []);
        }

        // Check if the absolute route exists in our routes array
        // - and if that route has the "any" type.
        if (isset($this->routes['any'][$this->url])) {
            return call_user_func_array($this->routes['any'][$this->url], []);
        }

        // Create an array of methods to check and loop through them
        $methods = [$this->method, 'any'];
        foreach ($methods as $method) {
            // Loop through the routes set for this method
            foreach ($this->routes[$method] as $route => $callable) {
                // Check if the route matches
                $regex = '/^' . str_replace('/', '\/', $route) . '/Uim';
                $matches = [];
                if (preg_match($regex, $this->url, $matches) === 1 && $matches[0] === $this->url) {
                    // First value in the array is the string that matched
                    array_shift($matches);

                    // Execute the callable method
                    return call_user_func_array($callable, $matches);
                }
            }
        }

        // No route found, check if we have an empty route.
        // - We should always have an empty route.
        if (!isset($this->routes['any']['/'])) {
            // No empty route found, throw an exception
            throw new \Exception("No \"not found\" route set.");
        }

        // Return the empty route's callable
        return call_user_func_array($this->routes['any']['/'], []);
    }
}
