<?php

namespace App\Enums;

enum OrderStatus: int
{
    case IN_PROGRESS = 1;
    case COMPLETED = 2;
}
