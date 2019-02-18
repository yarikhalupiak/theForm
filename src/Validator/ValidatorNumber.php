<?php

declare(strict_types = 1);

namespace App\Validator;

class ValidatorNumber extends ValidatorBase
{
    protected $type = 'number';

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  max: The maximum value allowed
     *  min: The minimum value allowed
     *  step: The range step
     *
     * @param array $values An array of options
     * @param array $options An array of values
     * @see ValidatorBase
     */
    protected function configure(array $values, array $options): void
    {
        $this->addValue('min', 1);
        $this->addValue('max', 100);
        $this->addValue('step', 1);
    }

    /**
     * @see ValidatorBase
     */
    protected function doCleanValues(): bool
    {
        if (!is_numeric($this->getValue('max'))) {
            throw new \InvalidArgumentException('max is not integer.');
        }

        if (!is_numeric($this->getValue('min'))) {
            throw new \InvalidArgumentException('min is not integer.');
        }

        return true;
    }
}
