<?php

namespace Simplon\Form\View\Elements;

use Simplon\Form\Data\Field;

/**
 * Interface ElementInterface
 * @package Simplon\Form\View\Elements
 */
interface ElementInterface
{
    /**
     * @return array|null
     */
    public function getAssets();

    /**
     * @return string|null
     */
    public function getCode();

    /**
     * @return string
     */
    public function getWide();

    /**
     * @return string
     */
    public function getWidgetHtml();

    /**
     * @return Field
     */
    public function getField();

    /**
     * @return string|null
     */
    public function getLabel();

    /**
     * @return string|null
     */
    public function renderLabel();

    /**
     * @return string
     */
    public function renderWidget();

    /**
     * @return string|null
     */
    public function renderErrors();

    /**
     * @return string
     */
    public function renderElement();
}