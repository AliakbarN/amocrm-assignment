<?php

namespace App\Services;

use DateInterval;

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

    public static function generateDate(int $numWeekdays) :int
    {
        $date = new \DateTime();
        for ($i = 0; $i < $numWeekdays; $i++) {
            // Add one day at a time and check if it's a weekday
            do {
                $date->add(new DateInterval('P1D'));
            } while ($date->format('N') >= 6); // 6 and 7 are Saturday and Sunday
        }

        return $date->getTimestamp();
    }
}
