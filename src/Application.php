<?php

namespace Codevia\Venus;

use Codevia\Venus\Utils\Http\Input\InputInterface;
use FastRoute\Dispatcher as FastRouteDispatcher;

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

}
