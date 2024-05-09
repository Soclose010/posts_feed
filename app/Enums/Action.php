<?php

namespace App\Enums;

enum Action: string
{
    case Create = "create";
    case Update = "update";
    case Delete = "delete";
    case Upgrade = "upgrade";
    case Downgrade = "downgrade";
}
