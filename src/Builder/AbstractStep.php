<?php

declare(strict_types = 1);

namespace App\Builder;

use App\Form\FormInterface;
use App\Scheme\SchemeId;

abstract class AbstractStep
{
    /**
     * @var SchemeId
     */
    protected $id;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @var string
     */
    protected $step;

    /**
     * @var bool
     */
    protected $prepared = false;

    /**
     * @var ElementCollection
     */
    protected $allElements;

    /**
     * @var ElementCollection
     */
    protected $elements;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * AbstractStep constructor.
     * @param ContainerInterface $container
     * @param FormInterface $form
     * @param SchemeId $id
     * @param string $step
     */
    public function __construct(
        ContainerInterface $container,
        FormInterface $form,
        SchemeId $id,
        string $step
    )
    {
        $this->container = $container;
        $this->form = $form;
        $this->id = $id;
        $this->step = $step;
    }

    /**
     * @param ElementCollection $elements
     */
    public function setElements(ElementCollection $elements)
    {
        $this->elements = $elements;
    }

    /**
     * @param ElementCollection $elements
     */
    public function setAllElements(ElementCollection $elements)
    {
        $this->allElements = $elements;
    }

    public function prepareForm()
    {
        $this->initElementGroups();

        /* @var AbstractElement $element */
        foreach ($this->elements as $element) {
            $element->processRequest($this->container);
            $element->init($this->id, $this->container);
            $element->configure($this->form, $this->step, $this->container);
        }

        $this->prepared = true;
    }

    public function saveForm()
    {
        /* @var AbstractElement $element */
        foreach ($this->allElements as $element) {
            $element->save($this->id, $this->container);
        }
    }

    /**
     * @return FormInterface
     */
    public function getForm(): FormInterface
    {
        if (!$this->isPrepared()) {
            $this->prepareForm();
        }

        return $this->form;
    }

    /**
     * @return bool
     */
    protected function isPrepared(): bool
    {
        return $this->prepared;
    }

    abstract function initElementGroups();
}