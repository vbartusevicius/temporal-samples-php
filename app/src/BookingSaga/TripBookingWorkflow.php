<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\BookingSaga;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow;

class TripBookingWorkflow implements TripBookingWorkflowInterface
{
    /** @var \Temporal\Internal\Workflow\ActivityProxy|BookHotelActivityInterface */
    private $bookHotelActivity;

    /** @var \Temporal\Internal\Workflow\ActivityProxy|ReserveCarActivityInterface */
    private $reserveCarActivity;

    public function __construct()
    {
        $this->bookHotelActivity = Workflow::newActivityStub(
            BookHotelActivityInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::hour(1))
                ->withRetryOptions(RetryOptions::new()->withMaximumAttempts(1))
                ->withTaskQueue('app1')
        );

        $this->reserveCarActivity = Workflow::newActivityStub(
            ReserveCarActivityInterface::class,
            ActivityOptions::new()
                ->withStartToCloseTimeout(CarbonInterval::hour(1))
                ->withRetryOptions(RetryOptions::new()->withMaximumAttempts(1))
                ->withTaskQueue('app2')
        );
    }

    public function bookTrip(string $name)
    {
        $saga = new Workflow\Saga();

        // Configure SAGA to run compensation activities in parallel
        $saga->setParallelCompensation(true);

        try {

            $hotelReservationID = yield $this->bookHotelActivity->bookHotel($name);
            $saga->addCompensation(fn() => yield $this->bookHotelActivity->cancelHotel($hotelReservationID, $name));

            $carReservationID = yield $this->reserveCarActivity->reserveCar($name);
            $saga->addCompensation(fn() => yield $this->reserveCarActivity->cancelCar($carReservationID, $name));

            return [
                'car' => $carReservationID,
                'hotel' => $hotelReservationID,
            ];
        } catch (\Throwable $e) {
            yield $saga->compensate();
            throw $e;
        }
    }
}