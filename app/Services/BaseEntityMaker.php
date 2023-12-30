<?php

namespace App\Services;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Models\BaseApiModel;
use AmoCRM\Models\CustomFields\TextCustomFieldModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;

abstract class BaseEntityMaker
{
    use CustomFieldsGenerator;

    protected array $entityCustomFields;


    abstract public function generate(AmoCRMAPI $api) :BaseApiModel|LinksCollection;

    abstract protected function generateEntityName() :string;
}
