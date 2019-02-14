<?php

declare(strict_types = 1);

namespace App\Validator;

class ValidatorFile extends ValidatorBase
{
    protected $type = 'file';

    /**
     * Configures the current validator.
     *
     * Available options:
     *
     *  * extension_title: name of the group extensions
     *  * max: max file size value
     *  * extensions: array with extensions
     *  * size_options: array with size format

     * @param array $options An array of options
     * @param array $values An array of values
     * @see ValidatorBase
     */
    protected function configure($values, $options): void
    {
        $this->addValue('extension_title', '');
        $this->addValue('extensions', '');
        $this->addValue('max', '');

        $this->addOption('size_options', ['K', 'M', 'G']);
    }

    /**
     * @see ValidatorBase
     */
    protected function doCleanValues(): bool
    {
        $extensions = explode(';', $this->getValue('extensions'));

        if (is_array($extensions)) {
            foreach ($extensions as $extension) {
                if ($extension && !preg_match('/[a-zA-Z0-9]/', $extension)) {
                    throw new \InvalidArgumentException($extension . ' is not supported.');
                }
            }
        }

        $size = substr($this->getValue('max'), 0, -1);
        $sizeFormat = substr($this->getValue('max'), -1);

        if (!is_numeric($size) && in_array($sizeFormat, $this->getOption('size_options'))) {
            throw new \InvalidArgumentException('Incorrect file size ' . $this->getValue('max'));
        }

        return true;
    }
}
