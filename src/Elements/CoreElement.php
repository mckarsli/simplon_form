<?php

namespace Simplon\Form\Elements;

use Simplon\Form\Filters\Core\CoreFilterInterface;
use Simplon\Form\Rules\Core\CoreRuleInterface;

/**
 * CoreElement
 * @package Simplon\Form\Elements
 * @author Tino Ehrich (tino@bigpun.me)
 */
abstract class CoreElement implements CoreElementInterface
{
    /**
     * @var string
     */
    protected $elementHtml = '<input type="text" class=":class" id=":id" name=":id" value=":value">';

    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $value;

    /**
     * @var array
     */
    protected $class = [];

    /**
     * @var array
     */
    protected $assetFiles = [];

    /**
     * @var array
     */
    protected $assetInlines = [];

    /**
     * @var CoreRuleInterface[]
     */
    protected $rules = [];

    /**
     * @var CoreFilterInterface[]
     */
    protected $filters = [];

    /**
     * @var bool
     */
    protected $postValue = false;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var array
     */
    protected $errorMessages = [];

    /**
     * @var string
     */
    protected $errorContainerWrapper = 'ul';

    /**
     * @var string
     */
    protected $errorItemWrapper = 'li';

    /**
     * @var string
     */
    protected $pathWebAssets = '';

    /**
     * @return string
     */
    public function getPathWebAssets()
    {
        return rtrim($this->pathWebAssets, '/');
    }

    /**
     * @param string $pathAssets
     *
     * @return static
     */
    public function setPathWebAssets($pathAssets)
    {
        $this->pathWebAssets = $pathAssets;

        return $this;
    }

    /**
     * @param $tag
     * @param $value
     * @param $string
     *
     * @return string
     */
    protected function replaceFieldPlaceholder($tag, $value, $string)
    {
        return (string)str_replace(":$tag", $value, $string);
    }

    /**
     * @param array $pairs
     * @param $string
     *
     * @return string
     */
    protected function replaceFieldPlaceholderMany(array $pairs, $string)
    {
        foreach ($pairs as $tag => $value)
        {
            $string = $this->replaceFieldPlaceholder($tag, $value, $string);
        }

        return $string;
    }

    /**
     * @return string
     */
    protected function getErrorContainerWrapper()
    {
        return $this->errorContainerWrapper;
    }

    /**
     * @return string
     */
    protected function getErrorItemWrapper()
    {
        return $this->errorItemWrapper;
    }

    /**
     * @param $elementHtml
     *
     * @return $this
     */
    public function setElementHtml($elementHtml)
    {
        $this->elementHtml = $elementHtml;

        return $this;
    }

    /**
     * @return string
     */
    public function getElementHtml()
    {
        return $this->elementHtml;
    }

    /**
     * @return string
     */
    protected function renderElementHtml()
    {
        return $this->parseFieldPlaceholders($this->getElementHtml());
    }

    /**
     * @param mixed $description
     *
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->description;
    }

    /**
     * @return null|string
     */
    protected function renderDescription()
    {
        $description = $this->getDescription();
        $template = '<p>:description</p>';

        if (empty($description))
        {
            return null;
        }

        return $this->parseFieldPlaceholders($template);
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return (string)$this->id;
    }

    /**
     * @param string $label
     *
     * @return static
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return (string)$this->label;
    }

    /**
     * @return string
     */
    protected function renderLabel()
    {
        $template = '<label for=":id">:label</label>';

        return $this->parseFieldPlaceholders($template);
    }

    /**
     * @param string $value
     *
     * @return static
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->hasPostValue() === true ? $this->getPostValue() : $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return static
     */
    public function addClass($value)
    {
        $this->class[] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassString()
    {
        return join(' ', $this->class);
    }

    /**
     * @param CoreRuleInterface[] $rules
     *
     * @return static
     */
    public function setRules(array $rules)
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param CoreRuleInterface $rule
     *
     * @return static
     */
    public function addRule(CoreRuleInterface $rule)
    {
        $this->rules[] = $rule;

        return $this;
    }

    /**
     * @return CoreRuleInterface[]
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @param CoreFilterInterface[] $filters
     *
     * @return static
     */
    public function setFilters(array $filters)
    {
        $this->filters = $filters;

        return $this;
    }

    /**
     * @param CoreFilterInterface $filter
     *
     * @return static
     */
    public function addFilter(CoreFilterInterface $filter)
    {
        $this->filters[] = $filter;

        return $this;
    }

    /**
     * @return CoreFilterInterface[]
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @return bool
     */
    public function hasPostValue()
    {
        return $this->getPostValue() !== false;
    }

    /**
     * @return bool|mixed
     */
    public function getPostValue()
    {
        return $this->postValue;
    }

    /**
     * @param $postValue
     *
     * @return CoreElementInterface
     */
    public function setPostValue($postValue)
    {
        $this->postValue = $postValue;

        return $this;
    }

    /**
     * @return static
     */
    public function processFilters()
    {
        $filters = $this->getFilters();

        if (empty($filters) === false)
        {
            foreach ($filters as $filterInstance)
            {
                $filterInstance->processFilter($this);
            }
        }

        return $this;
    }

    /**
     * @return bool|null
     */
    public function processRules()
    {
        $rules = $this->getRules();

        if (empty($rules))
        {
            return null;
        }

        foreach ($rules as $ruleInstance)
        {
            $isValid = $ruleInstance->isValid($this);

            if ($isValid === false)
            {
                $this->addErrorMessage($ruleInstance->renderErrorMessage($this));
            }
        }

        return true;
    }

    /**
     * @param $message
     */
    protected function addErrorMessage($message)
    {
        $this->errorMessages[] = "<{$this->getErrorItemWrapper()}>{$message}</{$this->getErrorItemWrapper()}>";
    }

    /**
     * @return array
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * @return string
     */
    public function renderErrorMessages()
    {
        $placeholders = [
            'containerWrapper'    => $this->getErrorContainerWrapper(),
            'errorMessagesString' => join('', $this->getErrorMessages()),
        ];

        $template = '<:containerWrapper class="rule-error-messages text-danger list-unstyled">:errorMessagesString</:containerWrapper>';

        return $this->replaceFieldPlaceholderMany($placeholders, $template);
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $errorMessages = $this->getErrorMessages();

        return empty($errorMessages);
    }

    /**
     * @return array
     */
    protected function getFieldPlaceholders()
    {
        return [
            'id'          => $this->getId(),
            'label'       => $this->getLabel(),
            'value'       => $this->getValue(),
            'class'       => $this->getClassString(),
            'description' => $this->getDescription(),
        ];
    }

    /**
     * @param $stringWithPlaceholders
     *
     * @return string
     */
    public function parseFieldPlaceholders($stringWithPlaceholders)
    {
        return $this->replaceFieldPlaceholderMany($this->getFieldPlaceholders(), $stringWithPlaceholders);
    }

    /**
     * @param $fileAsset
     *
     * @return $this
     */
    public function addAssetFile($fileAsset)
    {
        $this->assetFiles[] = $this->getPathWebAssets() . '/' . $fileAsset;

        return $this;
    }

    /**
     * @return array
     */
    public function getAssetFiles()
    {
        return $this->assetFiles;
    }

    /**
     * @param $inline
     *
     * @return $this
     */
    public function addAssetInline($inline)
    {
        $this->assetInlines[] = trim($inline);

        return $this;
    }

    /**
     * @return array
     */
    public function getAssetInlines()
    {
        return $this->assetInlines;
    }

    /**
     * @return array
     */
    public function render()
    {
        return [
            'label'       => $this->renderLabel(),
            'description' => $this->renderDescription(),
            'element'     => $this->renderElementHtml(),
        ];
    }
}