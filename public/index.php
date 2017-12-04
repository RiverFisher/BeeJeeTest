<?php

/**
 * Front controller
 */

use App\Models\User;

/**
 * Composer
 */
$loaderPath = dirname(__DIR__) . '/vendor/autoload.php';

if (!is_readable($loaderPath)) {
    throw new LogicException('Run php composer.phar install at first');
}

$loader = require $loaderPath;
$modelPath = substr(__DIR__, 0, strlen(__DIR__) - 7) . '/App/Models/';

session_start();
if (!isset($_SESSION['authenticated'])) {
    $_SESSION['authenticated'] = false;
}
if (!isset($_SESSION['app_user'])) {
    $_SESSION['app_user'] = new User(
        null,
        null,
        null,
        null,
        ['ROLE_GUEST'],
        null,
        null,
        null,
        new \DateTime('NOW'),
        new \DateTime('NOW')
    );
}

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

$router->add('user', ['controller' => 'UserController', 'action' => 'index']);
$router->add('user/new', ['controller' => 'UserController', 'action' => 'new']);
$router->add('user/edit/{id:\d+}', ['controller' => 'UserController', 'action' => 'edit']);
$router->add('user/delete/{id:\d+}', ['controller' => 'UserController', 'action' => 'delete']);

$router->add('role', ['controller' => 'RoleController', 'action' => 'index']);
$router->add('role/new', ['controller' => 'RoleController', 'action' => 'new']);
$router->add('role/edit/{id:\d+}', ['controller' => 'RoleController', 'action' => 'edit']);
$router->add('role/delete/{id:\d+}', ['controller' => 'RoleController', 'action' => 'delete']);

$router->add('login', ['controller' => 'SecurityController', 'action' => 'login']);
$router->add('logout', ['controller' => 'SecurityController', 'action' => 'logout']);
    
$router->dispatch($_SERVER['QUERY_STRING']);
