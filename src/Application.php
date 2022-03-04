<?php

namespace Codevia\Venus;

use Codevia\Venus\Utils\Http\Input\InputInterface;
use DI\Container;
use DI\ContainerBuilder;
use FastRoute\Dispatcher as FastRouteDispatcher;
use Laminas\Diactoros\ServerRequestFactory;
use Middlewares\ErrorFormatter\JsonFormatter;
use Middlewares\ErrorHandler;
use Middlewares\Utils\Dispatcher;
use Middlewares\RequestHandler;
use Psr\Container\ContainerInterface;

class Application
{
    private InputInterface $inputAdapter;
    private FastRouteDispatcher $dispatcher;
    private ?ContainerInterface $container = null;

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

    /**
     * Set the dispatcher with route definitions.
     *
     * @param FastRouteDispatcher $dispatcher
     * @return Application
     */
    public function setDispatcher(FastRouteDispatcher $dispatcher): self
    {
        $this->dispatcher = $dispatcher;
        return $this;
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
     * Execute the application logic
     *
     * @return void
     */
    public function run(): void
    {
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $this->inputAdapter::getParsedBody(),
            $_COOKIE,
            $_FILES
        );

        $dispatcher = new Dispatcher([
            new \Middlewares\Emitter(),

            new ErrorHandler([
                new JsonFormatter()
            ]),

            (new \Middlewares\PhpSession())->name('VENUSSESSID')
                ->regenerateId(60), // Prevent session fixation attacks

            (new \Middlewares\FastRoute($this->dispatcher))->attribute('handler'),

            (new RequestHandler($this->getContainer()))->handlerAttribute('handler'),
        ]);

        $dispatcher->dispatch($request);
    }
}
