<?php

namespace Example;

use Codevia\Venus\Controller\Controller;
use DI\Annotation\Inject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

class TestHandler extends Controller
{
    public function getTest(Request $request, Handler $handler): Response
    {
        return $this->createResponse($request, $handler, ['ok' => true]);
    }
}
