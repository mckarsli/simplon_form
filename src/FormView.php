<?php

namespace Simplon\Form;

use Simplon\Form\Security\Csrf;
use Simplon\Form\View\Elements\SubmitElement;
use Simplon\Form\View\RenderHelper;
use Simplon\Phtml\Phtml;
use Simplon\Phtml\PhtmlException;

/**
 * Class FormView
 * @package Simplon\Form
 */
class FormView
{
    /**
     * @var string
     */
    private $scope;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method = 'POST';

    /**
     * @var string
     */
    private $acceptCharset = 'utf-8';

    /**
     * @var SubmitElement
     */
    private $submitElement;

    /**
     * @var FormBlock[]
     */
    private $blocks;

    /**
     * @var Csrf
     */
    private $csrf;

    /**
     * @var bool
     */
    private $renderErrorMessage = true;

    /**
     * @var string
     */
    private $errorTitle = 'Looks like we are missing some information.';

    /**
     * @var string
     */
    private $errorMessage = 'Please have a look at the error messages below.';

    /**
     * @var bool
     */
    private $hasErrors;

    /**
     * @param $scope
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     *
     * @return FormView
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param string $method
     *
     * @return FormView
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getAcceptCharset()
    {
        return $this->acceptCharset;
    }

    /**
     * @return SubmitElement
     */
    public function getSubmitElement()
    {
        return $this->submitElement;
    }

    /**
     * @param SubmitElement $element
     *
     * @return FormView
     */
    public function setSubmitElement(SubmitElement $element)
    {
        $this->submitElement = $element;

        return $this;
    }

    /**
     * @return boolean
     */
    public function shouldRenderErrorMessage()
    {
        return $this->renderErrorMessage;
    }

    /**
     * @param boolean $renderErrorMessage
     *
     * @return FormView
     */
    public function setRenderErrorMessage($renderErrorMessage)
    {
        $this->renderErrorMessage = $renderErrorMessage === true;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorTitle()
    {
        return $this->errorTitle;
    }

    /**
     * @param string $errorTitle
     *
     * @return FormView
     */
    public function setErrorTitle($errorTitle)
    {
        $this->errorTitle = $errorTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * @param string $errorMessage
     *
     * @return FormView
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return Csrf
     */
    public function getCsrf()
    {
        return $this->csrf;
    }

    /**
     * @param Csrf $csrf
     *
     * @return FormView
     */
    public function setCsrf(Csrf $csrf)
    {
        $this->csrf = $csrf;

        return $this;
    }

    /**
     * @param string $id
     *
     * @return FormBlock
     * @throws FormException
     */
    public function getBlock($id)
    {
        if (isset($this->blocks[$id]))
        {
            return $this->blocks[$id];
        }

        throw new FormException('Requested FormElement "' . $id . '" does not exist');
    }

    /**
     * @param FormBlock $block
     *
     * @return FormView
     * @throws FormException
     */
    public function addBlock(FormBlock $block)
    {
        if (isset($this->blocks[$block->getId()]))
        {
            throw new FormException('FormBlock "' . $block->getId() . '" has already been set');
        }

        $this->blocks[$block->getId()] = $block;

        return $this;
    }

    /**
     * @param FormBlock[] $blocks
     *
     * @return FormView
     */
    public function setBlocks(array $blocks)
    {
        foreach ($blocks as $block)
        {
            $this->addBlock($block);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function hasErrors()
    {
        if ($this->hasErrors === null)
        {
            foreach ($this->blocks as $block)
            {
                foreach ($block->getRows() as $row)
                {
                    foreach ($row->getElements() as $element)
                    {
                        if ($element->getField()->hasErrors())
                        {
                            $this->hasErrors = true;
                        }
                    }
                }
            }
        }

        return $this->hasErrors;
    }

    /**
     * @param string $pathTemplate
     * @param array $params
     *
     * @return string
     * @throws PhtmlException
     */
    public function render($pathTemplate, array $params = [])
    {
        $params = array_merge($params, ['formView' => $this]);
        $form = (new Phtml())->render($pathTemplate, $params);

        /** @noinspection HtmlUnknownAttribute */
        $html = '<form {attrs}>{error}{scope}{csrf}{form}</form>';

        $class = ['ui', 'form', 'large'];

        if ($this->hasErrors())
        {
            $class[] = 'warning';
        }

        $placeholders = [
            'attrs' => RenderHelper::attributes(
                '{attrs}',
                [
                    'attrs' => [
                        'action'         => $this->getUrl(),
                        'method'         => $this->getMethod(),
                        'accept-charset' => $this->getAcceptCharset(),
                        'class'          => $class,
                    ],
                ]
            ),
            'error' => $this->shouldRenderErrorMessage() ? $this->renderErrorMessage() : null,
            'scope' => $this->renderScopeElement(),
            'csrf'  => $this->renderCsrfElement(),
            'form'  => $form,
        ];

        return RenderHelper::placeholders($html, $placeholders);
    }

    /**
     * @return string|null
     */
    public function renderErrorMessage()
    {
        if ($this->hasErrors())
        {
            return RenderHelper::placeholders(
                '<div class="ui warning message">{title}{message}</div>',
                [
                    'title'   => $this->getErrorTitle() ? '<div class="header">' . $this->getErrorTitle() . '</div>' : null,
                    'message' => $this->getErrorMessage() ? '<p>' . $this->getErrorMessage() . '</p>' : null,
                ]
            );


        }

        return null;
    }

    /**
     * @return string|null
     */
    private function renderScopeElement()
    {
        return '<input type="hidden" name="form[' . $this->getScope() . ']" value="1">';
    }

    /**
     * @return string|null
     */
    private function renderCsrfElement()
    {
        if ($this->getCsrf())
        {
            return $this->getCsrf()->renderElement();
        }

        return null;
    }
}