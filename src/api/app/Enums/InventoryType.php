<?php

namespace App\Enums;

enum InventoryType: int
{
    case IN = 1;
    case OUT = 0;
    case SELL = 2;
    case RETURN = 3;
    case SHIPMENT = 4;
    case REFUND = 5;
    case CORRECTION = 6;
    case DECOMPOSED = 7;
}
