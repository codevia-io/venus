<?php

use Codevia\Venus\Application;
use Codevia\Venus\Utils\Http\Input\JsonInput;
use Example\Permission;

// Fixes for the PhpSession middleware
ini_set('session.use_cookies', '0');
ini_set('session.cache_limiter', '');

/** @see https://getcomposer.org/doc/01-basic-usage.md#autoloading */
require_once __DIR__ . '/vendor/autoload.php';

// Instanciate the application
$app = new Application();
$config = $app->getConfig();

/** @var FastRoute\Dispatcher */
$dispatcher = require __DIR__ . '/router.php';

$config->setInputAdapter(new JsonInput); // Set the way the app reads request input
$config->setDispatcher($dispatcher); // Give the app the FastRoute Dispatcher

// If you want to define route access levels
$config->setPermisions(new Permission(
    Permission::PUBLIC // This is the default (usualy public) permission
));

$app->run();
