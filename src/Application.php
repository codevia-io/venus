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
        $request = ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $this->getConfig()->getInputAdapter()::getParsedBody(),
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

            (new \Middlewares\FastRoute($this->getConfig()->getDispatcher()))->attribute('handler'),

            (new RequestHandler($this->getConfig()->getContainer()))->handlerAttribute('handler'),
        ]);

        $dispatcher->dispatch($request);
    }
}
