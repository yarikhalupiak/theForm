<?php

namespace App\Form;

interface FormInterface
{
    public function setWidgets(array $widgets);

    public function addWidgets(array $widgets);

    public function addValidators(array $validators);

    public function addDefaults(array $defaults);
}