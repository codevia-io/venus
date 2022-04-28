<?php

namespace Codevia\Venus\Controller;

use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\RequestHandler;
use Psr\Http\Message\ResponseInterface;

abstract class Controller
{
    protected function createResponse(
        ServerRequest $request,
        RequestHandler $handler,
        mixed $content = [],
        int $code = 200
    ): ResponseInterface {
        // Convert content to JSON
        $content = json_encode($content);

        // Create the response object
        $response = $handler->handle($request);

        // Set correct headers
        $response = $response->withHeader('Content-Type', 'application/json');

        // Set response code
        $response->withStatus($code);

        // Write response to body
        $response->getBody()->write($content);

        return $response;
    }
}
