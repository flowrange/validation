<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Fixed value validation
 *
 * * The strict property tells if we use === (true) or == (false)
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class ValueRule extends Rule
{


    /**
     * Error : value not allowed
     * @var string
     */
    const ERR_NOT_ALLOWED = 'value.not-allowed';


    /**
     * Allowed value
     * @var mixed
     */
    private $allowedValue;


    /**
     * Strict comparison
     * @var bool
     */
    private $strict = true;


    /**
     * Constructor
     *
     * @param mixed $allowedValue Allowed value
     */
    public function __construct($allowedValue)
    {
        parent::__construct();

        $this->allowedValue = $allowedValue;
    }


    /**
     * Tell the rule to use strict (===) comparison or not (==)
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
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        return RuleCheckResult::make(
            $this->strict ? ($this->allowedValue === $value) : ($this->allowedValue == $value),
            $value,
            self::ERR_NOT_ALLOWED);
    }

}
