<?php

namespace Codevia\Venus\Middleware;

use Codevia\Venus\Utils\Permission\PermissionList;
use Middlewares\Utils\RequestHandlerContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class Permission implements MiddlewareInterface
{
    /**
     * @var ContainerInterface Used to resolve the handlers
     */
    private $container;

    /**
     * @var bool
     */
    private $continueOnEmpty = false;

    /**
     * @var string Attribute name for handler reference
     */
    private $handlerAttribute = 'request-handler';

    /**
     * Set the resolver instance.
     */
    public function __construct(ContainerInterface $container = null)
    {
        $this->container = $container ?: new RequestHandlerContainer();
    }

    /**
     * Set the attribute name to store handler reference.
     */
    public function handlerAttribute(string $handlerAttribute): self
    {
        $this->handlerAttribute = $handlerAttribute;

        return $this;
    }

    /**
     * Configure whether continue with the next handler if custom requestHandler is empty.
     */
    public function continueOnEmpty(bool $continueOnEmpty = true): self
    {
        $this->continueOnEmpty = $continueOnEmpty;

        return $this;
    }

    /**
     * Check a permission level against a permission mask.
     *
     * @param int $mask   The permission mask to check against.
     * @param int $level  The permission level to check.
     *
     * @return bool True if the permission level is allowed, false otherwise.
     */
    public static function checkMask(int $mask, int $level): bool
    {
        return ($mask & $level) === $level;
    }

    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface {
        $requestHandler = $request->getAttribute($this->handlerAttribute);

        if (empty($requestHandler)) {
            if ($this->continueOnEmpty) {
                return $handler->handle($request);
            }

            throw new RuntimeException('Empty request handler');
        }

        if (
            is_array($requestHandler)
            && count($requestHandler) > 2
            && is_int($requestHandler[2])
        ) {
            /** @var PermissionList */
            $permissions = $this->container->get('PermissionList');
            $permissions->checkValidity();

            $this->checkMask($requestHandler[2], $_SESSION['permission'] ?? 0);
        }

        return $handler->handle($request);

        throw new RuntimeException(sprintf('Invalid request handler: %s', gettype($requestHandler)));
    }
}
