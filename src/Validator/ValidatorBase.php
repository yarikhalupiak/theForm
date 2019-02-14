<?php

declare(strict_types = 1);

namespace App\Validator;

abstract class ValidatorBase
{
    /**
     * Key storing the title of the field
     */
    const KEY_TITLE = 'title';

    /**
     * Key storing the type of the field
     */
    const KEY_TYPE = 'type';

    /**
     * Key storing the value of the field
     */
    const KEY_VALUES = 'values';

    /**
     * @var array
     */
    static public $defaultFieldKeys = [
        self::KEY_TITLE,
        self::KEY_TYPE,
        self::KEY_VALUES,
    ];

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var array
     */
    protected $values = [];

    /**
     * @var array
     */
    protected $notValidate = [];

    /**
     * Available options:
     *
     * required:    true if the value is required, false otherwise (default to true)
     * trim:        true if the value must be trimmed, false otherwise (default to false)
     * empty_values: empty value when options is not required
     */
    protected $options = [
        'required' => true,
        'trim' => false,
        'empty_values' => true
    ];

    /**
     * @param array $values An array of values
     * @param array $options An array of options
     * @param array $notValidate An array of values who do not need validation
     */
    public function __construct(array $values = [], array $options = [], array $notValidate = [])
    {
        $this->notValidate = $notValidate;
        $this->configure($values, $options);

        if ($diff = array_diff_key(
            array_keys($values),
            array_keys($this->getValuesWithoutNotValidate())
        )) {
            throw new \InvalidArgumentException(sprintf(
                '%s does not support the following values key: \'%s\'.',
                get_class($this),
                implode('\', \'', $diff)
            ));
        }

        $this->values = $this->arrayMerge($this->values, $values);
        $this->options = $this->arrayMerge($this->options, $options);
    }

    /**
     * Configures the current validator.
     *
     * This method allows each validator to add values and options
     * during validator creation.
     *
     * @param array $values An array of values
     * @param array $options An array of options
     *
     * @see __construct()
     */
    abstract protected function configure(array $values, array $options): void;

    /**
     * Gets an value.
     *
     * @param  string $name The value name
     *
     * @return mixed  The value
     */
    public function getValue($name)
    {
        return isset($this->values[$name]) ? $this->values[$name] : null;
    }

    /**
     * Adds a new value with a default value.
     *
     * @param string $name The value name
     * @param mixed $value The default value
     *
     * @return ValidatorBase The current validator instance
     */
    public function addValue($name, $value = null)
    {
        $this->values[$name] = $value;

        return $this;
    }

    /**
     * Changes an value.
     *
     * @param string $name The value name
     * @param mixed $value The value
     *
     * @return ValidatorBase The current validator instance
     */
    public function setValue($name, $value)
    {
        if (!in_array($name, array_keys($this->values))) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s does not support the following option: \'%s\'.',
                    get_class($this), $name
                )
            );
        }

        $this->values[$name] = $value;

        return $this;
    }

    /**
     * Returns true if the value exists.
     *
     * @param  string $name The value name
     *
     * @return bool true if the value exists, false otherwise
     */
    public function hasValue($name)
    {
        return isset($this->values[$name]);
    }

    /**
     * Returns all values.
     *
     * @return array An array of values
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Changes all values.
     *
     * @param array $values An array of values
     *
     * @return ValidatorBase The current validator instance
     */
    public function setValues($values)
    {
        $this->values = $values;

        return $this;
    }

    /**
     * Gets an option value.
     *
     * @param  string $name The option name
     *
     * @return mixed  The option value
     */
    public function getOption($name)
    {
        return isset($this->options[$name]) ? $this->options[$name] : null;
    }

    /**
     * Adds a new option value with a default value.
     *
     * @param string $name The option name
     * @param mixed $value The default value
     *
     * @return ValidatorBase The current validator instance
     */
    public function addOption($name, $value = null)
    {
        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Changes an option value.
     *
     * @param string $name The option name
     * @param mixed $value The value
     *
     * @return ValidatorBase The current validator instance
     */
    public function setOption($name, $value)
    {
        if (!in_array($name, $this->options)) {
            throw new \InvalidArgumentException(
                sprintf(
                    '%s does not support the following option: \'%s\'.',
                    get_class($this), $name
                )
            );
        }

        $this->options[$name] = $value;

        return $this;
    }

    /**
     * Returns true if the option exists.
     *
     * @param  string $name The option name
     *
     * @return bool true if the option exists, false otherwise
     */
    public function hasOption($name)
    {
        return isset($this->options[$name]);
    }

    /**
     * Returns all options.
     *
     * @return array An array of options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Changes all options.
     *
     * @param array $values An array of options
     *
     * @return ValidatorBase The current validator instance
     */
    public function setOptions($values)
    {
        $this->options = $values;

        return $this;
    }

    /**
     * Cleans values.
     *
     * This method is also responsible for trimming the options
     * and checks whether the values is correctly.
     *
     * @return bool return true if the values are set correctly
     *
     * @throws \Exception
     */
    public function check(): bool
    {
        $clean = $this->getValuesWithoutNotValidate();

        if ($this->options['trim'] && is_string($clean)) {
            $clean = trim($clean);
        }

        if ($this->isEmpty($clean)) {

            if ($this->options['required']) {
                throw new \Exception('required');
            }

            return $this->getEmptyValues();
        }

        $this->values = $clean;

        return $this->doCleanValues();
    }

    /**
     * Checks whether the values is correctly.
     *
     * Every subclass must implements this method.
     *
     * @return mixed return true if the values are set correctly
     *
     * @throws \Exception
     */

    abstract protected function doCleanValues(): bool;

    /**
     * Returns true if the data is empty.
     *
     * @param  mixed $values
     *
     * @return bool true if the data is empty, false otherwise
     */
    protected function isEmpty($values): bool
    {
        if (is_array($values) && !empty($values)) {
            foreach ($values as $value) {

                if (is_array($values)) {
                    return $this->isEmpty($value);
                }

                return in_array($value, [null, ''], true);
            }
        }

        return in_array($values, [null, '', []], true);
    }

    /**
     * Returns an empty data for this validator.
     *
     * @return bool The empty data for this validator
     */
    protected function getEmptyValues(): bool
    {
        return $this->getOption('empty_values');
    }

    /**
     * Returns all values with non notValidate values.
     *
     * @return array array representation of the values
     */
    protected function getValuesWithoutNotValidate(): array
    {
        $values = $this->values;
        // remove notValidate values
        foreach ($this->notValidate as $key => $item) {
            if (array_key_exists($key, $values) && $item == true) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    protected function arrayMerge(array $first, array $second): array
    {
        $result = array_filter($first, function ($v, $k) use ($second) {
            if (isset($second[$k]) && gettype($v) != gettype($second[$k])) {
                throw new \InvalidArgumentException(
                    'Element with key ' . $k . ' has incorrect  type value'
                );
            }
        }, ARRAY_FILTER_USE_BOTH);

        return $result;
    }
}
