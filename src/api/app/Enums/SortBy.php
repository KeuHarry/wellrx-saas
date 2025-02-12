<?php

namespace App\Enums;

enum SortBy: string
{
    case CREATED_AT = 'created_at';
    case ID = 'id';
    case NAME = 'name';
    case CODE = 'code';
    case FIRSTNAME = 'first_name';
    case LASTNAME = 'last_name';
    case LABEL = 'label';
    case PRESCRIPTION_DATE = 'prescription_date';
}
