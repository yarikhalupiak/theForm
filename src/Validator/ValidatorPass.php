<?php

declare(strict_types = 1);

namespace App\Validator;

class ValidatorPass extends ValidatorBase
{
    protected $type = 'pass';

    protected function configure(array $values, array $options): void
    {
       $this->addOption('required', false);
    }

    /**
     * @see ValidatorBase
     */
    protected function doCleanValues(): bool
    {
        return true;
    }
}
