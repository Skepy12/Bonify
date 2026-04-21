<?php

namespace App\Enums;

enum ServiceMode: string
{
    case Offline = 'offline';
    case Online = 'online';
    case Hybrid = 'hybrid';
}
