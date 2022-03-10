<?php

namespace Codevia\Venus\Utils\Permission;

class PermissionList
{
    /**
     * Check if masks constants are bitwise valid.
     */
    public function checkValidity(): void
    {
        // Get all contants
        $reflection = new \ReflectionClass($this);
        $constants = $reflection->getConstants();

        // Check if all constants are integers
        foreach ($constants as $key => $constant) {
            if (!is_int($constant)) {
                throw new \InvalidArgumentException(
                    "PermissionList constant $key is not an integer"
                );
            }
        }

        // Sort constants by value
        asort($constants, SORT_NUMERIC);

        // Check if all values are bitwise
        $lastValue = 0.5;
        foreach ($constants as $key => $constant) {
            if ($constant != $lastValue * 2) {
                throw new \InvalidArgumentException(
                    "PermissionList constant $key is not a bitwise"
                );
            }
            $lastValue = $constant;
        }
    }
}
