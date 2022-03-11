<?php

namespace Codevia\Venus;

use Codevia\Venus\Middleware\RequestHandler;
use Laminas\Diactoros\ServerRequestFactory;
use Middlewares\ErrorFormatter\JsonFormatter;
use Middlewares\ErrorHandler;
use Middlewares\Utils\Dispatcher;

class Application
{
    private Config $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Execute the application logic
     *
     * @return void
     */
    public function run(): void
    {
        $requestBody = $this->getConfig()->getInputAdapter()::getParsedBody();
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $requestBody,
            $_COOKIE,
            $_FILES
        );

        $queue = [];

        $queue[] = new \Middlewares\Emitter();
        $queue[] = new ErrorHandler([new JsonFormatter()]);
        $queue[] = (new \Middlewares\PhpSession())->name('VENUSSESSID')
                ->regenerateId(60); // Prevent session fixation attacks

        $queue[] = (new \Middlewares\FastRoute(
            $this->getConfig()->getDispatcher()
        ))->attribute('handler');

        $queue[] = (new RequestHandler(
            $this->getConfig()->getContainer()
        ))->handlerAttribute('handler');

        $dispatcher = new Dispatcher([$queue]);
        $dispatcher->dispatch($request);
    }
}
