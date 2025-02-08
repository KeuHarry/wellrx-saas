<?php

namespace App\Enums;

enum CashRegisterStatus: int
{
    case OPENED = 1;
    case CLOSED = 2;
}
