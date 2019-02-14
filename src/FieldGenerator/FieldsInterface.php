<?php

namespace App\FieldGenerator;

interface FieldsInterface
{
    public function getWidget(): string;

    public function getValidator(): string;

    public function setFormFormat(string $formFormatter);

    public function getFormFormat(): string;

    public function setDecorator(string $decoratorFormat);

    public function getDecorator(): string;

    public function setFieldsTypes(array $fieldsTypes);

    public function getFieldsTypes(): array;

    public function setFieldsValues(array $fieldsValues);

    public function getFieldsValues(): array;

    public function setFieldsAdvanced(array $fieldsAdvanced);

    public function getFieldsAdvanced(): array;

    public function setFieldsLabels(array $fieldsLabels);

    public function getFieldsLabels(): array;
}