<?php

declare(strict_types=1);

namespace Temporal\Samples\BookingSaga;

use Carbon\Carbon;

class BookHotelActivity implements BookHotelActivityInterface
{
    public function bookHotel(string $name): string
    {
        return sprintf('Booking: %s - %s', $name, Carbon::now()->toDateTimeString());
    }

    public function cancelHotel(string $reservationID, string $name): string
    {
        return sprintf('Compensate booking: %s - %s', $name, Carbon::now()->toDateTimeString());
    }
}