<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PROCESSED = "Processed";
    case CONFIRMED = "Confirmed";
    case SENT = "Sent";
    case FINISHED = "Finished";
    case CANCELLED = "Cancelled";
}
