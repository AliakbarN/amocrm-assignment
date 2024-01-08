<?php
declare(strict_types=1);

namespace App\Services\EntityMakers;

use AmoCRM\Collections\BaseApiCollection;
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


class ProductMaker extends BaseEntityMaker
{

    public const PRODUCT_COUNT = 2;

    /**
     * @throws InvalidArgumentException
     * @throws AmoCRMApiException
     * @throws AmoCRMMissedTokenException
     * @throws AmoCRMoAuthApiException
     */
    public function generate(AmoCRMAPI $api): BaseApiCollection
    {
        $productElementsCollection = new CatalogElementsCollection();
        $productElementsCollection = $this->makeProducts($productElementsCollection, self::PRODUCT_COUNT);

        // create 2 products
        $productsCatalog = $api->apiClient->catalogs()->get()->getBy('type', 'products');
        return $api->apiClient->catalogElements($productsCatalog->getId())->add($productElementsCollection);
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
