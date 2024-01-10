<?php

declare(strict_types=1);

namespace App\Services\EntityMakers;

use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\Customers\CustomerModel;
use App\Services\AmoCRMAPI;
use App\Services\BaseEntityMaker;

class CustomerMaker extends BaseEntityMaker
{
    protected ContactModel $contact;

    public function __construct(ContactModel $contact)
    {
        $this->contact = $contact;
    }

    public function generate(AmoCRMAPI $api): BaseApiModel|BaseApiCollection
    {
        $customer = new CustomerModel();
        $customer->setName($this->generateEntityName());

        $api->entitiesServices['customer']->addOne($customer);
        $api->link($api->entitiesServices['customer'], $customer, $this->contact);

        return $customer;
    }

    protected function generateEntityName(): string
    {
        return $this->contact->getName() . ' customer';
    }
}
