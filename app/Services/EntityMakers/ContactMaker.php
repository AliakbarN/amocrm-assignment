<?php

declare(strict_types=1);

namespace App\Services\EntityMakers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\EntitiesServices\Contacts;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel;
use App\Services\AmoCRMAPI;
use App\Services\BaseEntityMaker;

class ContactMaker extends BaseEntityMaker
{

    protected array $entityCustomFields = [
        'email',
        'phone',
        'age',
        'gender'
    ];

    public function __construct(array $fields)
    {
        $this->customFieldsValues = $fields;
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMMissedTokenException
     */
    public function generate(AmoCRMAPI $api): BaseApiModel
    {
        $contact = new ContactModel();

        $contact->setName($this->generateEntityName());
        $contact->setFirstName($this->customFieldsValues['firstName']);
        $contact->setLastName($this->customFieldsValues['lastName']);
        $contact->setCustomFieldsValues($this->generateFields($this->mergeModelTypeWithItsValue()));
        $contact->setResponsibleUserId($api->getResponsibleUserId());
        return $api->apiClient->contacts()->addOne($contact);
    }

    protected function generateEntityName(): string
    {
        return $this->customFieldsValues['firstName'] . ' ' . $this->customFieldsValues['lastName'];
    }
}
