<?php

namespace Example;

use Codevia\Venus\Utils\Permission\PermissionList;

class Permission extends PermissionList
{
    const PUBLIC = 1;
    const USER = 2;
    const ADMIN = 4;
}
