<?php

  namespace Esiform\Elements;

  abstract class AbstractElement
  {
    /** @var array */
    protected $_data = array();

    // ##########################################

    /**
     * @return AbstractElement
     */
    public static function init()
    {
      return new static();
    }

    // ##########################################

    /**
     * @param $key
     * @param $val
     * @return AbstractElement
     */
    protected function _setByKey($key, $val)
    {
      $this->_data[$key] = $val;

      return $this;
    }

    // ##########################################

    /**
     * @param $key
     * @return bool|mixed
     */
    protected function _getByKey($key)
    {
      if(! isset($this->_data[$key]) || empty($this->_data[$key]))
      {
        return FALSE;
      }

      return $this->_data[$key];
    }

    // ##########################################

    /**
     * @param $value
     * @return AbstractElement
     */
    public function setId($value)
    {
      $this->_setByKey('id', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getId()
    {
      return $this->_getByKey('id');
    }

    // ##########################################

    /**
     * @param $value
     * @return AbstractElement
     */
    public function setValue($value)
    {
      $this->_setByKey('value', $value);

      return $this;
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getValue()
    {
      return $this->_getByKey('value');
    }

    // ##########################################

    /**
     * @param $rule
     * @param null $condition
     * @param null $errorMessage
     * @return AbstractElement
     */
    public function addRule($rule, $condition = NULL, $errorMessage = NULL)
    {
      $rules = $this->_getByKey('rules');

      if(! $rules)
      {
        $rules = array();
      }

      $rules[] = array(
        'type'         => $rule,
        'condition'    => $condition,
        'errorMessage' => $errorMessage,
      );

      $this->_setByKey('rules', $rules);

      return $this;
    }

    // ##########################################

    /**
     * @return bool
     */
    public function hasRules()
    {
      $rules = $this->getRules();

      return ! empty($rules);
    }

    // ##########################################

    /**
     * @return bool|mixed
     */
    public function getRules()
    {
      return $this->_getByKey('rules');
    }

    // ##########################################

    /**
     * @param $fieldTemplate
     * @param $attributes
     * @return mixed
     */
    protected function _renderElement($fieldTemplate, $attributes)
    {
      $_renderedAttributes = array();

      foreach($attributes as $name => $value)
      {
        if($value !== FALSE)
        {
          $_renderedAttributes[] = $name . '="' . $value . '"';
        }
      }

      return str_replace(':attributes', join(' ', $_renderedAttributes), $fieldTemplate);
    }
  }