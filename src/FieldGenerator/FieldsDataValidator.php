<?php

declare(strict_types = 1);

namespace App\FieldGenerator;

class FieldsDataValidator
{
    const DECORATOR_FORMAT_ERROR = 'Decorator format should not be empty';

    const FORM_FORMAT_ERROR = 'Form format should not be empty';

    const ADVANCED_ERROR = 'Advanced field should not be empty';

    const LABELS_ERROR = 'Labels field should not be empty';

    const FIELDS_EMPTY_ERROR = 'Arrays must not be empty';

    const FIELDS_DIFF_ERROR = 'Arrays must match each other';

    /**
     * @var array
     */
    private $errors = [];

    /**
     * @param FieldsInterface $fields
     * @return bool
     */
    public function validate(FieldsInterface $fields)
    {
        $this->validateFormat($fields);
        $this->validateFields($fields);

        return count($this->errors) === 0;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param FieldsInterface $fields
     */
    private function validateFormat(FieldsInterface $fields): void
    {
        if ($this->isBlankOrNull($fields->getFormFormat())) {
            $this->addError(self::FORM_FORMAT_ERROR);
        }

        if ($this->isBlankOrNull($fields->getDecorator())) {
            $this->addError(self::DECORATOR_FORMAT_ERROR);
        }

        if ($this->isBlankOrNull($fields->getFieldsAdvanced())) {
            $this->addError(self::ADVANCED_ERROR);
        }

        if ($this->isBlankOrNull($fields->getFieldsLabels())) {
            $this->addError(self::LABELS_ERROR);
        }
    }

    /**
     * @param FieldsInterface $fields
     */
    private function validateFields(FieldsInterface $fields): void
    {
        if (empty($fields->getFieldsTypes()) &&
            empty($fields->getFieldsValues())
        ) {
            $this->addError(self::FIELDS_EMPTY_ERROR);
        }

        if (!empty(
            array_diff_key($fields->getFieldsValues(), $fields->getFieldsTypes())
        )) {
            $this->addError(self::FIELDS_DIFF_ERROR);
        }
    }

    /**
     * @param $text
     * @return bool
     */
    private function isBlankOrNull($text): bool
    {
        return '' === $text || null === $text;
    }

    /**
     * @param $string
     */
    private function addError(string $string)
    {
        $this->errors[] = $string;
    }
}
