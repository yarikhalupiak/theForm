<?php

declare(strict_types = 1);

namespace App\Validator;

class ValidatorChoice extends ValidatorBase
{

    protected $type = 'choice';

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * list:  contains an array with options values
     *  * classes: contains an array with the values ​​of the classes of options icons
     *
     * @param array $options An array of options
     * @param array $values An array of error values
     */
    protected function configure(array $values, array $options): void
    {
        $this->addValue('list', []);
        $this->addValue('classes', []);
    }

    protected function doCleanValues(): bool
    {
        if (array_diff_key(
            $this->getValue('list'),
            $this->getValue('classes')
        )) {
            throw new \InvalidArgumentException('Arrays list and classes must match each other');
        }

        foreach ($this->getValue('list') as $key => $value) {
            if (!is_string($key)) {
                throw new \InvalidArgumentException('the key ' . $key . ' must be a string');
            }
        }

        return true;
    }
}
