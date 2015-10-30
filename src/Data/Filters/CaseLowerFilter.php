<?php

namespace Simplon\Form\Data\Filters;

/**
 * Class CaseLowerFilter
 * @package Simplon\Form\Data\Filters
 */
class CaseLowerFilter implements FilterInterface
{
    /**
     * @param string $value
     *
     * @return string
     */
    public function apply($value)
    {
        if (function_exists('mb_convert_case'))
        {
            if (is_array($value))
            {
                foreach ($value as $k => $v)
                {
                    $value[$k] = $this->convert($v);
                }

                return $value;
            }

            return $this->convert($value);
        }

        return $value;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function convert($value)
    {
        return mb_convert_case($value, MB_CASE_LOWER, 'UTF-8');
    }
}