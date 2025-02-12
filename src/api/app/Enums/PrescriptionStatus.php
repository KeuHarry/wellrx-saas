<?php

namespace App\Enums;

enum PrescriptionStatus: int
{
    case IN_PROGRESS = 0;
    case COMPLETED = 1;
    case PAID = 2;
}
