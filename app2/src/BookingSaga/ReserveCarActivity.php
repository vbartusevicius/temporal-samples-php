<?php

declare(strict_types=1);

namespace Temporal\Samples\BookingSaga;

use Carbon\Carbon;
use Temporal\Exception\Failure\ApplicationFailure;

class ReserveCarActivity implements ReserveCarActivityInterface
{

    public function reserveCar(string $name): string
    {
        if (random_int(0, 5) === 0) {
            throw new ApplicationFailure('Car booking failed', 'BookingFailure', true);
        }
        return sprintf('Car: %s - %s', $name, Carbon::now()->toDateTimeString());
    }
    public function cancelCar(string $reservationID, string $name): string
    {
        return sprintf('Compensate Car: %s - %s', $name, Carbon::now()->toDateTimeString());
    }
}