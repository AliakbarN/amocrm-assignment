<?php

namespace App\Services;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Models\CustomFieldsValues\BaseCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\NumericCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\LeadModel;

trait CustomFieldsGenerator
{


    // example [phone => 'NumericCustomFieldValueModel::class']
    protected array $customFieldValuesModelType = [
        'phone' => MultitextCustomFieldValuesModel::class,
        'firstName' => TextCustomFieldValuesModel::class,
        'lastName' => TextCustomFieldValuesModel::class,
        'gender' => TextCustomFieldValuesModel::class,
        'email' => MultitextCustomFieldValuesModel::class,
        'age' => NumericCustomFieldValuesModel::class
    ];


    protected array $customFieldsIds = [
        'phone' => 'PHONE',
        'email' => 'EMAIL',
        'age' => 874423,
        'gender' => 874525
    ];

    // fields and values form form
    protected array $customFieldsValues = [];

    /**
     * @param array<BaseCustomFieldValuesModel> $fields
     * @return CustomFieldsValuesCollection
     *
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

            $customFieldValuesModel->setValues((new ($this->changeModelToCollection($customFieldValuesModelType, 0)))
                    ->add(
                        (new ($this->changeModelToCollection($customFieldValuesModelType, 1)))
                            ->setValue($data['value'])
                    )
                );


            $customFieldsValues->add($customFieldValuesModel);
        }

        return $customFieldsValues;
    }

    /**
     * @param string $modelName
     * @param int $mode, 1 0r 0
     * @return string
     */
    protected function changeModelToCollection(string $modelName, int $mode) :string
    {
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
                'id' => $this->customFieldsIds[$customField]
            ];
        }

        return $mergedArray;
    }
}
