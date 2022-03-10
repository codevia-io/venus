<?php

use Codevia\Venus\Application;
use Codevia\Venus\Utils\Http\Input\JsonInput;
use Example\Permission as P;
use Example\TestHandler;

// Fixes for the PhpSession middleware
ini_set('session.use_cookies', '0');
ini_set('session.cache_limiter', '');
ini_set('display_errors', '1');

require_once __DIR__ . '/vendor/autoload.php';

$p = new P();
$p->checkValidity();

$app = new Application();
$container = $app->getContainer();

/** @var FastRoute\Dispatcher */
$dispatcher = require __DIR__ . '/router.php';

$app->setInputAdapter(new JsonInput);
$app->setDispatcher($dispatcher);
$app->setContainer($container);

$app->run();
