<?php

declare(strict_types=1);

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\BaseApiCollection;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;

abstract class BaseEntityMaker
{
    use CustomFieldsGeneratorTrait;

    protected array $entityCustomFields;


    abstract public function generate(AmoCRMAPI $api) :BaseApiModel|BaseApiCollection;

    abstract protected function generateEntityName() :string;
}
