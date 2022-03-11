<?php

namespace Example\Controller;

use Codevia\Venus\Controller\Controller;
use Example\Permission;
use Psr\Http\Server\RequestHandlerInterface as Handler;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class LoginController extends Controller
{
    public function loginAsUser(Request $request, Handler $handler): Response
    {
        $_SESSION['permission'] = Permission::USER;
        return $this->createResponse($request, $handler, $_SESSION);
    }
}
