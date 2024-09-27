<?php

namespace App\Enums;

enum CommonStatus: String
{
    case PENDING = 'pending';
    case FAILED = 'failed';
    case COMPLETE = 'complete';
}
