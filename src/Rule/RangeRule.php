<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Range validation
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RangeRule extends Rule
{


    /**
     * Error : too low
     * @var string
     */
    const ERR_TOO_LOW = 'range.too-low';


    /**
     * Error : too high
     * @var string
     */
    const ERR_TOO_HIGH = 'range.too-high';


    /**
     * Min value
     * @var mixed
     */
    private $min;


    /**
     * Max value
     * @var mixed
     */
    private $max;


    /**
     * Precision (for floats)
     * @var int
     */
    private $precision;


    /**
     * Set min value
     *
     * @param int $min Min value
     *
     * @return self
     */
    public function min($min)
    {
        $this->min = $min;
        return $this;
    }


    /**
     * Set max value
     *
     * @param int $max Max value
     *
     * @return self
     */
    public function max($max)
    {
        $this->max = $max;
        return $this;
    }


    /**
     * Set the precision (for floats)
     *
     * @param int $precision Precision (how many digits after decimal separator to take into account)
     *
     * @return self
     */
    public function precision($precision)
    {
        $this->precision = $precision;
        return $this;
    }


    /**
     * Constructor
     *
     * @param int $min Min value
     * @param int $max Max value
     */
    public function __construct($min = null, $max = null)
    {
        $this->min = $min;
        $this->max = $max;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $valid = true;
        $error = '';

        if ($this->min !== null && (bccomp($value, $this->min, $this->precision) < 0)) {
            $valid = false;
            $error = self::ERR_TOO_LOW;
        }

        if ($this->max !== null && (bccomp($this->max, $value, $this->precision) < 0)) {
            $valid = false;
            $error = self::ERR_TOO_HIGH;
        }

        return RuleCheckResult::make($valid, $value, $error);
    }

}
