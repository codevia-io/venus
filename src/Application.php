<?php

namespace Codevia\Venus;

use Codevia\Venus\Utils\Http\Input\InputInterface;
use Codevia\Venus\Utils\RequestHandler;
use FastRoute\Dispatcher as FastRouteDispatcher;
use Laminas\Diactoros\ServerRequestFactory;
use Middlewares\ErrorFormatter\JsonFormatter;
use Middlewares\ErrorHandler;
use Middlewares\Utils\Dispatcher;
use Middlewares\Utils\RequestHandlerContainer;

class Application
{
    private InputInterface $inputAdapter;
    private FastRouteDispatcher $dispatcher;

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
        $container = new RequestHandlerContainer();

        $session = new \Middlewares\PhpSession();
        $session->name('VENUSSESSID')
            ->regenerateId(60); // Prevent session fixation attacks

        $dispatcher = new Dispatcher([
            new \Middlewares\Emitter(),
            new ErrorHandler([
                new JsonFormatter()
            ]),
            $session,
            (new \Middlewares\FastRoute($this->dispatcher))->attribute('handler'),
            (new RequestHandler($container))->handlerAttribute('handler'),
        ]);

        $dispatcher->dispatch($request);
    }
}
