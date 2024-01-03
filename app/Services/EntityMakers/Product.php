<?php

namespace App\Services\EntityMakers;

use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMMissedTokenException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Exceptions\InvalidArgumentException;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\ContactModel;
use App\Services\AmoCRMAPI;
use App\Services\BaseEntityMaker;


class Product extends BaseEntityMaker
{

    protected int $productNumber = 2;

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function generate(AmoCRMAPI $api): LinksCollection
    {
        $productElementsCollection = new CatalogElementsCollection();
        $productElementsCollection = $this->makeProducts($productElementsCollection, $this->productNumber);

        // create 2 products
        $productsCatalog = $api->appClient->catalogs()->get()->getBy('name', 'Товары');
        $productElementsCollection = $api->appClient->catalogElements($productsCatalog->getId())->add($productElementsCollection);

        $link = new LinksCollection();

        foreach ($productElementsCollection as $element)
        {
            $link->add(
                $element->setQuantity($this->productNumber)
            );
        }

        return $link;
    }

    protected function generateEntityName(): string
    {
        return '';
    }

    protected function makeProducts(CatalogElementsCollection $collection, int $count) : CatalogElementsCollection
    {
        for ($i = $count; $i > 0; $i--)
        {
            $collection->add((new CatalogElementModel())
                ->setName('Product ' . $i));
        }

        return $collection;
    }
}
