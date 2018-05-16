<?php
if (PHP_SAPI == 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];
    if (is_file($file)) {
        return false;
    }
}

set_error_handler(function ($severity, $message, $file, $line) {
    if (!(error_reporting() & $severity)) {
        // This error code is not included in error_reporting, so ignore it
        return;
    }
    throw new \ErrorException($message, 0, $severity, $file, $line);
});

require __DIR__ . '/../vendor/autoload.php';

session_start();

// Instantiate the app
$settings = require __DIR__ . '/../src/settings.php';

//To execute the function below
$settings['determineRouteBeforeAppMiddleware'] = true;
$settings['displayErrorDetails'] = true;

$app = new \Slim\App($settings);

$container = $app->getContainer();
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        // retrieve logger from $container here and log the error
        $response->getBody()->rewind();
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write("Oops, something's gone wrong!")
            ->write(var_dump($exception));
    };
};

$container['phpErrorHandler'] = function ($container) {
    return function ($request, $response, $error) use ($container) {
        // retrieve logger from $container here and log the error
        $response->getBody()->rewind();
        return $response->withStatus(500)
            ->withHeader('Content-Type', 'text/html')
            ->write("Oops, something's gone wrong!");
    };
};



//Enabling Cors
$app->add(function($request, $response, $next) {
    $route = $request->getAttribute("route");

    $methods = [];

    if (!empty($route)) {
        $pattern = $route->getPattern();

        foreach ($this->router->getRoutes() as $route) {
            if ($pattern === $route->getPattern()) {
                $methods = array_merge_recursive($methods, $route->getMethods());
            }
        }
        //Methods holds all of the HTTP Verbs that a particular route handles.
    } else {
        $methods[] = $request->getMethod();
    }

    $response = $next($request, $response);


    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader("Access-Control-Allow-Methods", implode(",", $methods));
});

// Set up dependencies
require __DIR__ . '/../src/dependencies.php';

// Register middleware
require __DIR__ . '/../src/middleware.php';

// Register routes
require __DIR__ . '/../src/routes.php';

// Run app
$app->run();
