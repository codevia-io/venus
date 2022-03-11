<?php

use Codevia\Venus\Application;
use Codevia\Venus\Utils\Http\Input\JsonInput;
use Example\Permission as P;

// Fixes for the PhpSession middleware
ini_set('session.use_cookies', '0');
ini_set('session.cache_limiter', '');

require_once __DIR__ . '/vendor/autoload.php';

$app = new Application();
$config = $app->getConfig();

/** @var FastRoute\Dispatcher */
$dispatcher = require __DIR__ . '/router.php';

$config->setInputAdapter(new JsonInput);
$config->setDispatcher($dispatcher);
$config->setPermisions(new P(P::PUBLIC));

$app->run();
