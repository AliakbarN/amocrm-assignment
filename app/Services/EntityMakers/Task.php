<?php

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

class Task extends BaseEntityMaker
{

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMMissedTokenException
     */
    public function generate(AmoCRMAPI $api): BaseApiModel
    {
        $task = new TaskModel();
        $task->setTaskTypeId(TaskModel::TASK_TYPE_ID_FOLLOW_UP);
        $task->setResponsibleUserId($api->getResponsibleUserId());
        $task->setText('The task should be done');
        $task->setCompleteTill($this->generateData(4));
        $task->setEntityType(EntityTypesInterface::LEADS);

        return $task;
    }

    protected function generateEntityName(): string
    {
        return '';
    }

    protected function generateData($numWeekdays) :int
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
