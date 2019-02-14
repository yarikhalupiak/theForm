<?php

declare(strict_types = 1);

namespace App\Validator;

class ValidatorDate extends ValidatorBase
{
    protected $type = 'date';

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     * format: contains correct date format
     *
     * @param array $values An array of options
     * @param array $options An array of values
     * @see ValidatorBase
     */
    protected function configure(array $values, array $options): void
    {
        $this->addValue('format', 'm/d/Y');
    }

    /**
     * @see ValidatorBase
     */
    protected function doCleanValues(): bool
    {
        $format = $this->getValue('format');
        $date = date($format);

        if ($format) {
            $dateTime = \DateTime::createFromFormat($format, $date);

            return ($dateTime && $dateTime->format($format) === $date);
        }

        return false;
    }
}
