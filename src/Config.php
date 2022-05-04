<?php

namespace Codevia\Venus;

use Codevia\Venus\Utils\Http\Input\InputInterface;
use Codevia\Venus\Utils\Permission\PermissionList;
use DI\Container;
use DI\ContainerBuilder;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

class Config
{
    private InputInterface $inputAdapter;
    private Dispatcher $dispatcher;
    private ?ContainerInterface $container = null;
    private PermissionList $permissions;
    private bool $usePermissions = false;
    /** @var MiddlewareInterface[] */
    private array $middlewares = [];

    public function getInputAdapter(): InputInterface
    {
        return $this->inputAdapter;
    }

    /**
     * Set the input adapter that corresponds to the format you are working with.
     *
     * @param InputInterface $inputAdapter
     * @return Application
     */
    public function setInputAdapter(InputInterface $inputAdapter): self
    {
        $this->inputAdapter = $inputAdapter;
        return $this;
    }

    public function getDispatcher(): Dispatcher
    {
        return $this->dispatcher;
    }

    /**
     * Set the dispatcher with route definitions.
     *
     * @param FastRouteDispatcher $dispatcher
     * @return Application
     */
    public function setDispatcher(Dispatcher $dispatcher): self
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    public function getContainer(): ContainerInterface | Container
    {
        if ($this->container === null) {
            $builder = new ContainerBuilder();
            $builder->useAnnotations(true);
            $this->container = $builder->build();
        }
        return $this->container;
    }

    /**
     * Set the container to use with middlewares.
     *
     * @param ContainerInterface $container
     * @return Application
     */
    public function setContainer(ContainerInterface $container): self
    {
        $this->container = $container;
        return $this;
    }

    public function setPermisions(PermissionList $permissions)
    {
        $this->permissions = $permissions;
        $this->usePermissions = true;
        $this->getContainer()->set(PermissionList::class, $this->permissions);
    }

    public function usePermission(): bool
    {
        return $this->usePermissions;
    }

    public function addMiddlware(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * @return MiddlewareInterface[]
     */
    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }
}
