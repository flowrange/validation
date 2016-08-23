<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Length validation
 *
 * * Works on int, so you may ask for example for a 3-digits integer
 * * To cater for utf-8 shenanigans, the value is utf8_decode'd (so no mbstring dependency)
 * * The rule forwards to next rule even if invalid : this allows for example to tell the user that an email address
 *   is too long *and* has a wrong format
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class LengthRule extends Rule
{


    /**
     * Error : too short
     * @var string
     */
    const ERR_TOO_SHORT = 'length.too-short';


    /**
     * Error : too long
     * @var string
     */
    const ERR_TOO_LONG = 'length.too-long';


    /**
     * Min length
     * @var int
     */
    private $min;


    /**
     * Max length
     * @var int
     */
    private $max;


    /**
     * Set min length
     *
     * @param int $min Min length
     *
     * @return self
     */
    public function min($min)
    {
        $this->min = $min;
        return $this;
    }


    /**
     * Set max length
     *
     * @param int $max Max length
     *
     * @return self
     */
    public function max($max)
    {
        $this->max = $max;
        return $this;
    }


    /**
     * Constructor
     *
     * @param int $minOrMaxIfAlone Either the max length if alone, or the min length if there's a $max
     * @param int $max             Max length
     */
    public function __construct($minOrMaxIfAlone = null, $max = null)
    {
        if ($max === null) {
            $this->max = $minOrMaxIfAlone;
        } else {
            $this->min = $minOrMaxIfAlone;
            $this->max = $max;
        }
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $length = strlen(utf8_decode((string)$value));
        $valid  = true;
        $error  = '';

        if ($this->min > 0 && $length < $this->min) {
            $valid = false;
            $error = self::ERR_TOO_SHORT;
        }

        if ($this->max > 0 && $length > $this->max) {
            $valid = false;
            $error = self::ERR_TOO_LONG;
        }

        return RuleCheckResult::make($valid, $value, $error, true);
    }
}
