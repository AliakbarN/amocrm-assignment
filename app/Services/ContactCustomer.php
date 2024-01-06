<?php

namespace App\Services;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Enum\Tags\TagColorsEnum;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
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

        if ($contact === false) {
            return false;
        } elseif ($contact === true) {
            return true;
        }

        $customer = new CustomerModel();
        $customer->setName($contact->getName() . ' customer');

        $api->entitiesServices['customer']->addOne($customer);

        $api->link($api->entitiesServices['customer'], $customer, $contact);

        return true;
    }

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMMissedTokenException
     */
    protected function getValidContact(ContactsCollection $contacts, AmoCRMAPI $api, string $fieldCode) :ContactModel|bool
    {
        $existingContact = $this->getNonUniqueContact($contacts, $fieldCode);

        if ($existingContact === null) {
            return false;
        }

        if (!$this->isContactLeadSucceeded($existingContact->getLeads(), $api)) {
            $tagsCollection = new TagsCollection();
            $tag = new TagModel();
            $tag->setName('There was an attempt to create a double of the contact' . $existingContact->getName());
            $tag->setColor(TagColorsEnum::LAPIS_LAZULI);
            $tagsCollection->add($tag);
            $tagsService = $api->appClient->tags(EntityTypesInterface::CONTACTS);

            try {
                $tagsService->add($tagsCollection);
                $existingContact->setTags($tagsCollection);
                $api->entitiesServices['contact']->updateOne($existingContact);
            } catch (AmoCRMoAuthApiException|AmoCRMApiException $e) {
                dd($e);
            }
            
            return true;
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
}
