<?php

namespace App\Enums;

enum InventoryType: int
{
    case IN = 1;
    case OUT = 0;
    case SELL = 2;
}
