<?php

namespace App\Services\EntityMakers;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\LeadModel;
use App\Services\AmoCRMAPI;

class ContactCustomer
{

    protected array $customFieldsValues = [];


    public function __construct(array $customFieldsValues)
    {
        $this->customFieldsValues = $customFieldsValues;
    }

    /**
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     */
    public function check(AmoCRMAPI $api, string $fieldCode) :bool
    {
        try {
            $contacts = $api->appClient->contacts()->get(with: (array)ContactModel::LEADS);
        } catch (\Exception $exception) {
            return false;
        }

        $contact = $this->getValidContact($contacts, $api, $fieldCode);

        if ($contact === null) {
            return false;
        }

        $customer = new CustomerModel();
        $customer->setName($this->generateName());

        $api->entitiesServices['customer']->addOne($customer);

        $api->link($api->entitiesServices['customer'], $customer, $contact);

        return true;
    }

    protected function getValidContact(ContactsCollection $contacts, AmoCRMAPI $api, string $fieldCode) :?ContactModel
    {
        $existingContact = $this->getNonUniqueContact($contacts, $fieldCode);

        if ($existingContact === null) {
            return null;
        }

        if (!$this->isContactLeadSucceeded($existingContact->getLeads(), $api)) {
            return null;
        }

        return $existingContact;
    }

    protected function isContactLeadSucceeded(?LeadsCollection $leads, AmoCRMAPI $api): bool
    {
        if ($leads !== null) {
            /** @var LeadModel $lead */
            foreach ($leads as $lead) {
                $lead = $api->entitiesServices['lead']->getOne($lead->getId());

                if ($lead->getStatusId() === LeadModel::WON_STATUS_ID) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function getNonUniqueContact(ContactsCollection $contacts, string $fieldCode) : ?ContactModel
    {
        /** @var ContactModel $contact */
        foreach ($contacts as $contact)
        {
            $contactsNumber = $contact->getCustomFieldsValues()
                ->getBy('fieldCode', $fieldCode)
                ->getValues();

            foreach ($contactsNumber as $phoneNumber) {
                if ($phoneNumber->getValue() === $this->customFieldsValues['phone']) {
                    return $contact; // существующий контакт
                }
            }
        }

        return null;
    }

    protected function generateName() :string
    {
        return $this->customFieldsValues['firstName'] . ' ' . $this->customFieldsValues['lastName'];
    }
}
