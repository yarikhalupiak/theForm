<?php

namespace App\Form;

class Form implements FormInterface
{
    private $form;

    private $defaults = [];

    public function __construct($class, $defaults = [])
    {
        if (!class_exists($class)) {
            throw new \RuntimeException("Class '$class' not found");
        }

        $this->form = new $class();
        $this->defaults = $defaults;
    }

    /**
     * Set widgets
     * @param array $widgets
     * @return $this
     */
    public function setWidgets(array $widgets)
    {
        $this->form->setWidgetSchema($widgets);

        return $this;
    }

    /**
     * Add array for widgets
     * @param array $widgets
     */
    public function addWidgets(array $widgets)
    {
        foreach ($widgets as $schema_name => $widget) {
            $this->form->setWidget($schema_name, $widget);
        }
    }

    /**
     * Add array for validators
     * @param array $validators
     */
    public function addValidators(array $validators)
    {
        foreach ($validators as $schema_name => $validator) {
            $this->form->setValidator($schema_name, $validator);
        }
    }

    /**
     * Set or extend current defaults
     * @param array $defaults
     */
    public function addDefaults(array $defaults)
    {
        $this->form->defaults = !empty($this->defaults)
            ? array_merge($this->defaults, $defaults)
            : $defaults;
    }
}