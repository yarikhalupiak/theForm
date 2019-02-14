<?php

declare(strict_types = 1);

namespace App\FieldGenerator;

use App\FieldGenerator\Exception\FieldsInvalidException;

class FieldsFactory
{
    /**
     * Type number allow integer and float values
     */
    const TYPE_NUMBER = 'number';

    /**
     * Type string allow any string value, with 255 characters limit
     */
    const TYPE_STRING = 'string';

    /**
     * Type checkbox add set of checkboxes with titles and custom values, also available modification with icons
     */
    const TYPE_CHECKBOXES = 'checkboxes';

    /**
     * Types select provide options select defined by admin
     */
    const TYPE_SELECT = 'select';

    /**
     * Year provide year selection with definition max and min year
     */
    const TYPE_YEAR = 'year';

    /**
     * Range provide 2 inputs for add min and max value
     */
    const TYPE_RANGE = 'range';

    /**
     * Type date provide date select interface, stored as d:m:Y value
     */
    const TYPE_DATE = 'date';

    /**
     * Type bool provide 1 checkbox input with title
     */
    const TYPE_BOOL = 'bool';

    /**
     * Type file provide ability to attach files by user, have advanced params like max size, and file types
     */
    const TYPE_FILE = 'file';

    /**
     * Type provide selection 1 choice from set of choices, also support icons
     */
    const TYPE_RADIO = 'radio';

    /**
     * Type attach allows you to attach a file in response
     */
    const TYPE_ATTACH = 'attach';

    /**
     *  Form widgets
     */
    const WIDGETS = 'widgets';

    /**
     *  Form validators
     */
    const VALIDATORS = 'validators';

    /**
     * @var FieldsInterface
     */
    private $fields;

    /**
     * @var FieldsDataValidator
     */
    private $validator;

    /**
     * @var array
     */
    private $fieldsValidators = [];

    /**
     * @var array
     */
    private $fieldsWidgets = [];

    /**
     * @var array
     */
    private $defaultValues = [];

    /**
     * FieldsFactory constructor.
     * @param FieldsInterface $fields
     * @param array $defaultValues
     */
    public function __construct(FieldsInterface $fields, array $defaultValues)
    {
        $this->fields = $fields;
        $this->defaultValues = $defaultValues;

        $this->validator = new FieldsDataValidator();
        $this->generate();
    }

    /**
     * @return FieldsDataValidator
     */
    public function getValidator(): FieldsDataValidator
    {
        return $this->validator;
    }

    /**
     * @return array
     */
    public function getFieldsWidgets():array
    {
        return $this->fieldsWidgets;
    }

    /**
     * @return array
     */
    public function getFieldsValidators(): array
    {
        return $this->fieldsValidators;
    }

    /**
     *  Generate widgets and field validators
     */
    private function generate(): void
    {
        try {
            if ($this->validator && !$this->validator->validate($this->fields)) {
                throw new FieldsInvalidException(
                    'Failed to validate fields', $this->validator->getErrors()
                );
            }

            foreach ($this->fields->getFieldsTypes() as $parentKey => $fieldsType) {
                $this->generateFieldWidgets($parentKey, $fieldsType);
                $this->generateFieldValidators($parentKey, $fieldsType);
            }
        } catch (FieldsInvalidException $exception) {
            throw new \RuntimeException(
                'Failed to generate field widgets and validators', $this->validator->getErrors()
            );
        }
    }

    /**
     * @param string $parentKey
     * @param array $fieldsType
     */
    private function generateFieldWidgets(string $parentKey, array $fieldsType): void
    {
        $decoratorClass = $this->fields->getDecorator();
        $widgetsClass = $this->fields->getWidget();

        $widgets = new $decoratorClass(
            new $widgetsClass(
                $this->fieldFactory(
                    $parentKey,
                    $fieldsType,
                    self::WIDGETS
                ), $this->fields->getFormFormat()
            )
        );

        $this->fieldsWidgets[$parentKey] = $widgets;
    }

    /**
     * @param string $parentKey
     * @param array $fieldsType
     */
    private function generateFieldValidators(string $parentKey, array $fieldsType): void
    {
        $validatorClass = $this->fields->getDecorator();

        $validators = new $validatorClass(
            $this->fieldFactory(
                $parentKey,
                $fieldsType,
                self::VALIDATORS
            )
        );

        $this->fieldsValidators[$parentKey] = $validators;
    }

    /**
     * @param string $parentKey
     * @param array $fieldsType
     * @param string $generate
     * @return array
     */
    private function fieldFactory(string $parentKey, array $fieldsType, string $generate): array
    {
        $fields = [];

        foreach ($fieldsType as $childKey => $type) {
            $values = $this->getFieldDefaultValues($type);

            if ($this->hasFieldValues($parentKey, $childKey)) {
                $values = $this->createFieldValues(
                    $this->getFieldValues($parentKey, $childKey),
                    $values
                );
            }

            switch ($generate) {
                case self::VALIDATORS:
                    $fields[$childKey] = $this->getInputValidator($type, $values);
                    break;
                case self::WIDGETS:
                    $fields[$childKey] = $this->getWidgetInput($type, $values);
                    break;
                default:
                    throw new FieldsInvalidException('Invalid argument ' . $generate);
            }
        }

        return $fields;
    }

    /**
     * @param $parentKey
     * @param $childKey
     * @return mixed
     */
    private function getFieldValues(string $parentKey, string $childKey)
    {
        return $this->fields->getFieldsValues()[$parentKey][$childKey];
    }

    /**
     * @param string $parentKey
     * @param string $childKey
     * @return bool
     */
    private function hasFieldValues(string $parentKey, string $childKey): bool
    {
        return (
            isset($this->fields->getFieldsValues()[$parentKey][$childKey])
            && !empty($this->fields->getFieldsValues()[$parentKey][$childKey])
        );
    }

    /**
     * @param array $values
     * @param array $defaultValues
     * @return array
     */
    private function createFieldValues(array $values, array $defaultValues): array
    {
        $values = array_merge($defaultValues, $values);

        if ($diff = array_diff_key(
            $values,
            $defaultValues
        )) {
            foreach ($diff as $key => $value) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    /**
     * @param string $type
     * @return mixed
     */
    private function getFieldDefaultValues(string $type)
    {
        if (isset($this->defaultValues[$type])) {
            return $this->defaultValues[$type];
        }

        throw new FieldsInvalidException('Invalid field type');
    }

    /**
     * @param string $type
     * @param array $values
     * @return mixed
     */
    private function getInputValidator(string $type, array $values)
    {
        /* TODO getInputValidator */
    }

    /**
     * @param string $type
     * @param array $values
     * @return mixed
     */
    private function getWidgetInput(string $type, array $values)
    {
        /* TODO getWidgetInput */
    }
}
