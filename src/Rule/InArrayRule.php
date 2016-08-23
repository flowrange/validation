<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * In Array validation
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class InArrayRule extends Rule
{


    /**
     * Error : value not allowed
     * @var string
     */
    const ERR_VALUE_NOT_ALLOWED = 'in-array.value-not-allowed';


    /**
     * Allowed values
     * @var array
     */
    private $allowedValues = [];


    /**
     * If true and check array values, tells whether use strict comparison or not
     * @var bool
     */
    private $strict = false;


    /**
     * If true, check allowedValues keys instead of values
     * @var bool
     */
    private $useKeys = false;


    /**
     * Tells whether to use strict comparison or not, when comparing to array values
     *
     * @param bool $strict
     *
     * @return self
     */
    public function strict($strict = true)
    {
        $this->strict = $strict;
        return $this;
    }


    /**
     * Set true if the values checked should exist as an array key
     *
     * @param bool $useKeys
     *
     * @return self
     */
    public function useKeys($useKeys = true)
    {
        $this->useKeys = $useKeys;
        return $this;
    }


    /**
     * Constructor
     *
     * @param type $allowedValues
     */
    public function __construct(array $allowedValues = [])
    {
        $this->allowedValues = $allowedValues;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $valid = $this->useKeys ?
                 array_key_exists($value, $this->allowedValues)
               : in_array($value, $this->allowedValues, $this->strict);

        return RuleCheckResult::make($valid, $value, self::ERR_VALUE_NOT_ALLOWED);
    }

}
