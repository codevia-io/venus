<?php

use Example\Controller\LoginController;
use Example\Controller\TestHandler;
use Example\Permission as P; // Shorthand so your lines wont be too long
use FastRoute\RouteCollector;

return \FastRoute\simpleDispatcher(function (RouteCollector $r) {
    $r->addRoute('GET', '/user', [TestHandler::class, 'getTest', P::USER]);
    $r->addRoute('GET', '/admin', [TestHandler::class, 'getTest', P::ADMIN]);
    $r->addRoute('GET', '/public', [TestHandler::class, 'getTest', P::PUBLIC]);

    $r->addGroup('/login', function (RouteCollector $r) {
        $r->addRoute('GET', '/user', [LoginController::class, 'loginAsUser', P::USER]);
    });
});
