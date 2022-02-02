<?php

namespace Codevia\Venus\Utils\Http\Input;

class GlobalInput implements InputInterface
{
    public static function getParsedBody(): array
    {
        return $_POST;
    }
}
