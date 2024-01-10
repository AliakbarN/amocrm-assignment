<?php

declare(strict_types=1);

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use App\Services\Helpers\ConfigHelper as Helper;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\EntitiesServices\BaseEntity;
use AmoCRM\EntitiesServices\Leads;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use AmoCRM\Models\LeadModel;
use AmoCRM\Models\UserModel;
use Fig\Http\Message\StatusCodeInterface;

class AmoCRMAPI
{
    public AmoCRMApiClient $apiClient;

    protected static array $customFieldsIdentifiers = [
        'phone' => 'PHONE',
        'email' => 'EMAIL',
        'age' => 874423,
        'gender' => 874525
    ];

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
        'product',
    ];

    public array $entitiesServices;

    /**
     * @throws AmoCRMMissedTokenException
     */
    public function __construct()
    {
        $this->apiClient = $this->initiateAmoCRMClient();
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
    public function link(BaseEntity $service, BaseApiModel $model, CanBeLinkedInterface $modelToLink = null, LinksCollection $preparedLink = null) :LinksCollection
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
        $users = $this->apiClient->users()->get();
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

        $this->entitiesServices['lead'] = $this->apiClient->leads();
        $this->entitiesServices['contact'] = $this->apiClient->contacts();
        $this->entitiesServices['task'] = $this->apiClient->tasks();
        $this->entitiesServices['customer'] = $this->apiClient->customers();
    }

    protected function registerEntitiesServices() :void
    {
        foreach ($this->availableEntities as $entity)
        {
            $this->entitiesServices[$entity] = null;
        }
    }

    public static function getCustomFieldIdentifier(string $fieldName) : string|int|null
    {
        if (!array_key_exists($fieldName, self::$customFieldsIdentifiers)) {
            return null;
        }

        return self::$customFieldsIdentifiers[$fieldName];
    }
}
