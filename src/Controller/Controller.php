<?php

namespace Codevia\Venus\Controller;

use Laminas\Diactoros\ServerRequest;
use Middlewares\Utils\RequestHandler;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class Controller
{
    protected Serializer $serializer;

    public function __construct()
    {
        $encoders = [new JsonEncoder()];
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object::class . ($object->getId() ?? '');
            },
        ];
        $normalizers = [new ObjectNormalizer(defaultContext: $defaultContext)];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    protected function serialize(object $object): string
    {
        return $this->serializer->serialize($object, 'json');
    }

    protected function createResponse(
        ServerRequest $request,
        RequestHandler $handler,
        mixed $content = [],
        int $code = 200
    ): ResponseInterface {
        // Serialize the content without circular references
        $content = $this->serialize($content);

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
