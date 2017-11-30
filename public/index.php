<?php

/**
 * Front controller
 */

/**
 * Composer
 */
$loaderPath = dirname(__DIR__) . '/vendor/autoload.php';

if (!is_readable($loaderPath)) {
    throw new LogicException('Run php composer.phar install at first');
}

$loader = require $loaderPath;
$modelPath = substr(__DIR__, 0, strlen(__DIR__) - 7) . '/App/Models/';

/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'HomeController', 'action' => 'index']);
//$router->add('{controller}/{action}');
$router->add('task', ['controller' => 'TaskController', 'action' => 'index']);
$router->add('task/new', ['controller' => 'TaskController', 'action' => 'new']);
$router->add('task/edit/{id:\d+}', ['controller' => 'TaskController', 'action' => 'edit']);
$router->add('task/complete/{id:\d+}', ['controller' => 'TaskController', 'action' => 'complete']);
$router->add('task/delete/{id:\d+}', ['controller' => 'TaskController', 'action' => 'delete']);
    
$router->dispatch($_SERVER['QUERY_STRING']);
