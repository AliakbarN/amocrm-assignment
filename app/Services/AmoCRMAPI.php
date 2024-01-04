<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\UserModel;

class AmoCRMAPI
{
    public AmoCRMApiClient $appClient;

    /**
     * @var array|string[]
     *
     * these entities that are used in the app
     */
    protected array $availableEntities = [
        'lead',
        'contact',
        'task',
        'customer',
        'product'
    ];

    public array $entitiesServices;

    /**
     * @throws AmoCRMMissedTokenException
     */
    public function __construct()
    {
        $this->appClient = $this->initiateAmoCRMClient();
        $this->initiateEntitiesServices();
    }

    protected function initiateAmoCRMClient() :AmoCRMApiClient
    {
        $client = new AmoCRMApiClient(...Helper::getAmoCRMClientConfig());

        $client->setAccountBaseDomain(config('amoCRM.base_domain'));

        $client->setAccessToken(TokenSaver::restore());

        return $client;
    }

    public function getAvailableEntities() :array
    {
        return $this->availableEntities;
    }

    // links entities
    public function link(mixed $service, mixed $model, mixed $modelToLink = null, LinksCollection $preparedLink = null) :LinksCollection
    {
        $links = null;

        if ($preparedLink !== null) {
            $links = $preparedLink;
        } else {
            $links = (new LinksCollection())->add($modelToLink);
        }


        return $service->link($model, $links);
    }

    /**
     * @throws AmoCRMApiException
     * @throws AmoCRMoAuthApiException
     * @throws AmoCRMMissedTokenException
     */
    public function getResponsibleUserId(): int
    {
        $users = $this->appClient->users()->get();
        /** @var UserModel $randomUser */
        $randomUser = collect($users)->random();

        return $randomUser->getId(); // random user
    }

    /**
     * @throws AmoCRMMissedTokenException
     */
    protected function initiateEntitiesServices() :void
    {
        $this->registerEntitiesServices();

        $this->entitiesServices['lead'] = $this->appClient->leads();
        $this->entitiesServices['contact'] = $this->appClient->contacts();
        $this->entitiesServices['task'] = $this->appClient->tasks();
        $this->entitiesServices['customer'] = $this->appClient->customers();
    }

    protected function registerEntitiesServices() :void
    {
        foreach ($this->availableEntities as $entity)
        {
            $this->entitiesServices[$entity] = null;
        }
    }
}
