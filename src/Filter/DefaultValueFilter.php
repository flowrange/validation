<?php

namespace Flowrange\Validation\Filter;

use Flowrange\Validation\Filter;

/**
 * Default value filter
 *
 * @author Florent
 */
class DefaultValueFilter extends Filter
{


    /**
     * Default value
     * @var mixed
     */
    private $defaultValue;


    /**
     * Constructor
     *
     * @param type $defaultValue
     */
    public function __construct($defaultValue)
    {
        $this->defaultValue = $defaultValue;
    }


    /**
     * Filter::filter
     */
    public function filter($value, \Flowrange\Validation\Validator $validator)
    {
        if ($value === '' || $value === null) {
            return $this->defaultValue;
        }
        return $value;
    }

}
