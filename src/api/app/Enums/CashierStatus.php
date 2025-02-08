<?php

namespace App\Enums;

enum CashierStatus: int
{
    case PENDING = 0;
    case IN_PROGRESS = 1;
    case PAID = 2;
}
