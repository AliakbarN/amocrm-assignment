<?php

declare(strict_types=1);

namespace App\Services\EntityMakers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\NumericCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use App\Services\AmoCRMAPI;
use App\Services\BaseEntityMaker;
use App\Services\CustomFieldsGeneratorTrait;

class LeadMaker extends BaseEntityMaker
{

    protected array $entityCustomFields = [

    ];

    public function __construct(array $fields)
    {
        $this->customFieldsValues = $fields;
    }

    //generate the entity with its custom fields

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMMissedTokenException
     */
    public function generate(AmoCRMAPI $api) : BaseApiModel
    {
        $lead = new LeadModel();

        $lead->setName($this->generateEntityName());
        $lead->setCustomFieldsValues($this->generateFields($this->mergeModelTypeWithItsValue()));
        $lead->setResponsibleUserId($api->getResponsibleUserId());

        return $api->apiClient->leads()->addOne($lead);
    }


    protected function generateEntityName() :string
    {
        return $this->customFieldsValues['firstName'] . ' ' . $this->customFieldsValues['lastName'] . "'s lead";
    }
}
