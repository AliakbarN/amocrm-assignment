<?php

declare(strict_types=1);

namespace App\Services;

use AmoCRM\Collections\ContactsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Collections\TagsCollection;
use AmoCRM\Enum\Tags\TagColorsEnum;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Helpers\EntityTypesInterface;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\TagModel;
use App\Services\AmoCRMAPI;
use App\Services\BaseEntityMaker;

class ContactCustomerMaker
{

    protected string|int $customFieldPhoneValue;
    protected ?ContactModel $contact = null;
    protected AmoCRMAPI $api;


    public function __construct(AmoCRMAPI $api, string|int $customFieldPhoneValue)
    {
        $this->customFieldPhoneValue = $customFieldPhoneValue;
        $this->api = $api;
    }

    public function isContactExists(string $fieldCode) :bool
    {
        $contacts = null;

        try {
            $contacts = $this->api->entitiesServices['contact']->get(with: (array)ContactModel::LEADS);
        } catch (\Exception $exception) {
            return false;
        }

        $existingContact = $this->getExistingContact($contacts, $fieldCode);

        if ($existingContact === null) {
            return false;
        } else {
            $this->contact = $existingContact;
            return true;
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function generateTag() :void
    {
        $tag = new TagModel();
        $tag->setName('There was an attempt to create a double of the contact - ' . $this->contact->getName());
        $tag->setColor(TagColorsEnum::LAPIS_LAZULI);

        $contactTags = $this->contact->getTags();

        if ($contactTags === null) {
            $this->contact->setTags((new TagsCollection())->add($tag));
        } else {
            $contactTags->add($tag);
        }

        $this->api->apiClient->tags(EntityTypesInterface::CONTACTS)->addOne($tag);
        $this->api->entitiesServices['contact']->updateOne($this->contact);
    }

    public function hasSuccessfulLead(): bool
    {
        $leads = $this->contact->getLeads();
        if ($leads !== null) {
            /** @var LeadModel $lead */
            foreach ($leads as $lead) {
                $lead = $this->api->entitiesServices['lead']->getOne($lead->getId());

                if ($lead->getStatusId() === LeadModel::WON_STATUS_ID) {
                    return true;
                }
            }
        }

        return false;
    }

    protected function getExistingContact(ContactsCollection $contacts, string $fieldCode) : ?ContactModel
    {
        /** @var ContactModel $contact */
        foreach ($contacts as $contact)
        {
            $contactsNumber = $contact->getCustomFieldsValues()
                ->getBy('fieldCode', $fieldCode)
                ->getValues();

            foreach ($contactsNumber as $phoneNumber) {
                if ($phoneNumber->getValue() === $this->customFieldPhoneValue) {
                    return $contact; // существующий контакт
                }
            }
        }

        return null;
    }

    public function getContact() :ContactModel
    {
        return $this->contact;
    }
}
