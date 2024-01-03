<?php

namespace App\Services;

use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\Interfaces\CanBeLinkedInterface;
use Exception;

class AmoCRMManager
{
    protected AmoCRMAPI $api;

    protected array $fields = [];
    /**
     * @var array<string, BaseApiModel|LinksCollection>
     *
     * example ['lead' => App\Services\EntityMakers\Lead]
     *
     */
    protected array $entities = [];

    protected array $entitiesClasses = [];

    public function __construct(AmoCRMAPI $api, array $fields)
    {
        $this->fields = $fields;
        $this->api = $api;
    }

    // business logic
    /**
     * @throws Exception
     */
    public function manage() :void
    {

        $contactCustomer = new ContactCustomer($this->fields);

        if ($contactCustomer->check($this->api, 'PHONE')) {
            return;
        }

        $this->initiateEntities();
        $this->checkEntities();

        // link contact to lead and vise versa
        $this->api->link($this->api->entitiesServices['contact'], $this->entities['contact'], $this->entities['lead']);
        $this->api->link($this->api->entitiesServices['lead'], $this->entities['lead'], $this->entities['contact']);

        // create task
        $this->api->entitiesServices['task']->addOne($this->entities['task']->setEntityId($this->entities['lead']->getId()));

        // link products to lead
        $this->api->link($this->api->entitiesServices['lead'], $this->entities['lead'], preparedLink: $this->entities['product']);
    }

    /**
     * @param array $entityMakers
     * @return void
     *
     * registers entity makers by user if user wants (manually)
     */
    public function registerEntityMakers(array $entityMakers = []) :void
    {
        $this->entitiesClasses = $entityMakers;
    }

    /**
     * @throws Exception
     */
    protected function initiateEntities() :void
    {
        if (count($this->entitiesClasses) === 0) {
            throw new Exception("The entity makers' classes have not been registered");
        }

        foreach ($this->entitiesClasses as $entity => $entityClass)
        {
            $this->entities[$entity] = (new $entityClass($this->fields))->generate($this->api);
        }
    }

    /**
     * @throws Exception
     *
     * checks accuracy of entity makers array
     */
    protected function checkEntities() :void
    {
        $availableEntities = $this->api->getAvailableEntities();

        foreach ($this->entities as $entity => $entityClass)
        {
            if (!in_array($entity, $availableEntities)) {
                throw new Exception('Check the entity makers, possibly you missed something');
            }
        }
    }
}
