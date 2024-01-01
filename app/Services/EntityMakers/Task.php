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
use App\Services\Helper;
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
        $task->setCompleteTill(Helper::generateDate(4));
        $task->setEntityType(EntityTypesInterface::LEADS);

        return $task;
    }

    protected function generateEntityName(): string
    {
        return '';
    }
}
