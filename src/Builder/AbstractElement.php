<?php

declare(strict_types = 1);

namespace App\Builder;

use App\Form\FormInterface;
use App\Scheme\SchemeId;

abstract class AbstractElement
{
    /**
     * @param array $values
     * @param ContainerInterface $container
     */
    public function doOnSubmit(array $values, ContainerInterface $container)
    {
        foreach ($values as $key => $value) {
            $container->set($key, $value);
        }
    }

    /**
     * @param ContainerInterface $container
     */
    public function processRequest(ContainerInterface $container)
    {
        /* TODO processRequest */
    }

    /**
     * @param array $values
     * @param ContainerInterface $container
     */
    public function doBeforeValidation(array $values, ContainerInterface $container)
    {
        /* TODO doBeforeValidation */
    }

    public abstract function configure(FormInterface $form, string $step, ContainerInterface $container);

    public abstract function init(SchemeId $id, ContainerInterface $container);

    public abstract function save(SchemeId $id, ContainerInterface $container);
}