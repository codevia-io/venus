<?php

use Example\Permission as P;
use Example\TestHandler;
use FastRoute\RouteCollector;

return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/user', [TestHandler::class, 'getTest', P::USER]);
    $r->addRoute('GET', '/admin', [TestHandler::class, 'getTest', P::ADMIN]);
    $r->addRoute('GET', '/public', [TestHandler::class, 'getTest', P::PUBLIC]);
});
