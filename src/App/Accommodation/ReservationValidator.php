<?php

namespace App\Accommodation;

use App\Room;
use App\User;
use Carbon\Carbon;

class ReservationValidator
{

    const MINIMUM_STAY_LENGTH = 1;
    const MAXIMUM_STAY_LENGTH = 15;
    const MAXIMUM_ROOMS = 4;

    /**
     * @param $start_date
     * @param $end_date
     * @param $rooms Room
     */
    public function validate($start_date, $end_date, $rooms)
    {
        $end = Carbon::createFromFormat('Y-m-d', $end_date);
        $start = Carbon::createFromFormat('Y-m-d', $start_date);

        if (! $this->daysAreWithinAcceptableRange($end, $start)) {
            throw new \InvalidArgumentException
            ('Requires a stay length from '
                . self::MINIMUM_STAY_LENGTH . ' to ' .
                self::MAXIMUM_STAY_LENGTH . ' days.');
        }
        if (! is_array($rooms)) {
            throw new \InvalidArgumentException('Requires last
                parameter rooms to be an array.');
        }
        if ($this->tooManyRooms($rooms)) {
            throw new \InvalidArgumentException('Cannot reserve
            more than ' . self::MAXIMUM_ROOMS . ' rooms.');
        }

        return $this;

    }

    /**
     *
     * @param $end Carbon
     * @param $start Carbon
     * @return bool
     */
    private function daysAreWithinAcceptableRange($end, $start)
    {
        if ($this->daysAreLessThanMinimumAllowed($end, $start)
            || $this->daysAreGreaterThanMaximumAllowed
            ($end, $start)
        ) {
            return false;
        } else {
            return true;
        }
    }

    private function daysAreLessThanMinimumAllowed($end, $start)
    {
        return $end->diffInDays($start) <
        self::MINIMUM_STAY_LENGTH;
    }

    private function daysAreGreaterThanMaximumAllowed($end, $start)
    {
        return $end->diffInDays($start) >
        self::MAXIMUM_STAY_LENGTH;
    }

    private function tooManyRooms($rooms)
    {
        return count($rooms) > self::MAXIMUM_ROOMS;
    }


}
