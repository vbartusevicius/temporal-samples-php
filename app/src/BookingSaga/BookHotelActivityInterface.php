<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Temporal\Samples\BookingSaga;

use Temporal\Activity\ActivityInterface;

#[ActivityInterface(prefix: "BookHotelActivity.")]
interface BookHotelActivityInterface
{
    /**
     * Request a hotel reservation.
     *
     * @param string $name customer name
     * @return string reservationID
     */
    public function bookHotel(string $name): string;

    /**
     * Cancel a hotel reservation.
     *
     * @param string name customer name
     * @param string reservationID id returned by bookHotel
     * @return string cancellationConfirmationID
     */
    public function cancelHotel(string $reservationID, string $name): string;
}