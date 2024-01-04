<?php

namespace App\Services;

use DateInterval;
use DateTime;

class Helper
{
    public static function getAmoCRMClientConfig() :array
    {
        return [
            config('amoCRM.client_id'),
            config('amoCRM.client_secret'),
            config('amoCRM.client_redirect_url')
        ];
    }

    public static function generateDate(int $numDays) :int
    {
        // Create a DateTime object from the start date
        $currentDate = new DateTime();

        // Define work hours
        $workStartHour = 9;
        $workEndHour = 18;

        // Loop through each day to add weekdays
        for ($i = 0; $i < $numDays; $i++) {
            do {
                // Add one day to the current date
                $currentDate->modify('+1 day');
            } while ($currentDate->format('N') >= 6); // Skip weekends (Saturday and Sunday)

            // Set the time to the start of the workday (9 am)
            $currentDate->setTime($workStartHour, 0, 0);

            // If the current time is after the end of the workday (6 pm), move to the next day
            if ($currentDate->format('H') >= $workEndHour) {
                $currentDate->modify('+1 day');
                $currentDate->setTime($workStartHour, 0, 0);
            }
        }

        // Return the result as a timestamp
        return $currentDate->getTimestamp();
    }
}
