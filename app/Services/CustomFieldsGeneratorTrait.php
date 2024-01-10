<?php

declare(strict_types=1);

namespace App\Services;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\LeadModel;
use Exception;

trait CustomFieldsGeneratorTrait
{

    const FIRST_MODE = 1;
    const SECOND_MODE = 2;

    // example [phone => 'NumericCustomFieldValueModel::class']
    protected array $customFieldValuesModelType = [
        'phone' => MultitextCustomFieldValuesModel::class,
        'firstName' => TextCustomFieldValuesModel::class,
        'lastName' => TextCustomFieldValuesModel::class,
        'gender' => TextCustomFieldValuesModel::class,
        'email' => MultitextCustomFieldValuesModel::class,
        'age' => NumericCustomFieldValuesModel::class
    ];

    // fields and values form form
    protected array $customFieldsValues = [];

    /**
     * @param array<BaseCustomFieldValuesModel> $fields
     * @return CustomFieldsValuesCollection
     *
     * @throws Exception
     */// generates custom fields dynamically
    protected function generateFields(array $fields) : CustomFieldsValuesCollection
    {
        $customFieldsValues = new CustomFieldsValuesCollection();

        foreach ($fields as $field => $data)
        {
            $customFieldValuesModelType = $data['modelType'];
            $customFieldValuesModel = new $customFieldValuesModelType();

            if (is_string($data['id'])) {
                $customFieldValuesModel->setFieldCode($data['id']);
            } else {
                $customFieldValuesModel->setFieldId($data['id']);
            }

            $customFieldValuesModel->setValues((new ($this->changeModelToCollection($customFieldValuesModelType, self::SECOND_MODE)))
                    ->add(
                        (new ($this->changeModelToCollection($customFieldValuesModelType, self::FIRST_MODE)))
                            ->setValue($data['value'])
                    )
                );


            $customFieldsValues->add($customFieldValuesModel);
        }

        return $customFieldsValues;
    }

    /**
     * @param string $modelName
     * @param int $mode , 1 0r 0
     * @return string
     * @throws Exception
     * @example mode 1 - returns custom fields values model, mode 0 - returns custom fields value collection's values model
     *
     */
    protected function changeModelToCollection(string $modelName, int $mode) :string
    {
        if ($mode !== self::FIRST_MODE & $mode !== self::SECOND_MODE) {
            throw new Exception('the mose must be FIRST_MODE or SECOND_MODE');
        }

        $explodedClassName = explode('\\', $modelName);

        if ($mode === 1) {
            return 'AmoCRM\\Models\\CustomFieldsValues\\ValueModels\\' . str_replace('Values', 'Value', array_pop($explodedClassName));
        }

        return 'AmoCRM\\Models\\CustomFieldsValues\\ValueCollections\\' . str_replace('ValuesModel', 'ValueCollection', array_pop($explodedClassName));
    }

    /**
     * @return array
     *
     * @example [David => TextCustomFieldModel::class]
     */
    protected function mergeModelTypeWithItsValue() :array
    {
        $mergedArray = [];

        foreach ($this->entityCustomFields as $customField)
        {
            $mergedArray[$customField] = [
                'modelType' => $this->customFieldValuesModelType[$customField],
                'value' => $this->customFieldsValues[$customField],
                'id' => AmoCRMAPI::getCustomFieldIdentifier($customField)
            ];
        }

        return $mergedArray;
    }
}
