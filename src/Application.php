<?php

namespace Codevia\Venus;

use Codevia\Venus\Utils\Http\Input\InputInterface;

class Application
{
    private InputInterface $inputAdapter;
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

}
