<?php

namespace App\Enums;

enum OrderStatus: string
{
  case NEW = "New";
    case PROCESSED = "Processed";
    case CONFIRMED = "Confirmed";
    case SENT = "Sent";
    case FINISHED = "Finished";
    case CANCELLED = "Cancelled";
}
