<?php

namespace App\Enums;

enum PaymentMethod: String
{
    case ORANGE_MONEY  = 'orange-money';
    case MTN_MOBILE_MONEY = 'mtn-mobile-money';
}
