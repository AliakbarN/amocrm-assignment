<?php

declare(strict_types=1);

namespace App\Services\EntityMakers;

use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\TaskModel;
use App\Services\AmoCRMAPI;
use App\Services\BaseEntityMaker;
use DateInterval;
use DateTime;
use Exception;

class TaskMaker extends BaseEntityMaker
{

    /**
     * @throws Exception
     */
    public function generate(AmoCRMAPI $api): BaseApiModel
    {
        $task = new TaskModel();
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_FOLLOW_UP);
        $task->setText('The task should be done');
        $task->setCompleteTill(self::generateDate(4));
        $task->setEntityType(EntityTypesInterface::LEADS);

        return $task;
    }


    public static function generateDate(int $numDays) :int
    {
        // Create a DateTime object from the start date
        $currentDate = new DateTime();

        // Define work hours
        $workStartHour = 9;
        $workEndHour = 18;

        for ($i = 0; $i < $numDays; $i++) {
            // Add one day to the current date
            $currentDate->modify('+1 day');

            // Set the time to the start of the workday (9 am)
            $currentDate->setTime($workStartHour, 0, 0);

            // If the current time is after the end of the workday (6 pm), move to the next day
            if ($currentDate->format('H') >= $workEndHour) {
                $currentDate->modify('+1 day');
                $currentDate->setTime($workStartHour, 0, 0);
            }
        }

        // If the final date falls on a weekend, adjust to Monday
        if ($currentDate->format('N') >= 6) {
            $currentDate->modify('next Monday');
        }

        // Return the result as a timestamp
        return $currentDate->getTimestamp();
    }

    protected function generateEntityName(): string
    {
        return '';
    }
}
