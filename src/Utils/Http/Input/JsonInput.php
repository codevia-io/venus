<?php

namespace Codevia\Venus\Utils\Http\Input;

class JsonInput implements InputInterface
{
    public static function getParsedBody(): array
    {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}
