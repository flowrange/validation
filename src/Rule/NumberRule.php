<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Float validation
 *
 * * Due to obvious precision issues, and that rules are mainly used to validate user input, all valid values are
 *   returned as strings
 * * When rounded, values are rounded up (123.456789 => 123.457)
 * * Numbers are normalized : .123 -> 0.123, 123. -> 123, 123.000 -> 123
 * * Not suitable for money (use the money rule which transforms values to integers)
 * * If keepEmpty is true (by default), empty values ('' or null) are returned as null. This allows one to
 *   differentiate empty and 0
 * * If keepEmpty is false, empty values ('' or null) are changed to '0.0'
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class NumberRule extends Rule
{


    /**
     * Float parsing regexp
     * @var string
     */
    const FLOAT_REGEXP = '/^-?(\d+)?[.,]?(\d+)?$/u';


    /**
     * Error : not a number
     * @var string
     */
    const ERR_NOT_A_NUMBER = 'number.not-a-number';


    /**
     * Error : the value must be positive
     * @var string
     */
    const ERR_MUST_BE_POSITIVE = 'number.must-be-positive';


    /**
     * If the number must be positive
     * @var bool
     */
    private $allowNegative = false;


    /**
     * Tells to not cast empty values to int
     * @var bool
     */
    private $keepEmpty = true;


    /**
     * Precision
     * @var int
     */
    private $precision = 8;


    /**
     * Allow negative integers
     * @return self
     */
    public function allowNegative($allowNegative = true)
    {
        $this->allowNegative = $allowNegative;
        return $this;
    }


    /**
     * Tell to keep empty values as empty instead of casting to int
     *
     * @param bool $keepEmpty
     *
     * @return self
     */
    public function keepEmpty($keepEmpty = true)
    {
        $this->keepEmpty = $keepEmpty;
        return $this;
    }


    /**
     * Sets the precision
     *
     * @param int $precision Precision
     *
     * @return self
     */
    public function precision($precision)
    {
        $this->precision = (int)$precision;
        return $this;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        if ($value === '' || $value === null) {
            return RuleCheckResult::make(true, $this->keepEmpty ? null : '0');
        }

        $valid =
               is_float($value)
            || is_double($value)
            || is_int($value)
            || ctype_digit($value)
            || (is_string($value) && preg_match(self::FLOAT_REGEXP, $value));

        if($valid) {

            $stringValue = $value;
            if (is_string($value)) {
                $stringValue = strtr($value, ',', '.');
            }

            $stringValue = rtrim(\sprintf('%0.0' . $this->precision . 'F', $stringValue), '.0');

            if (!$this->allowNegative && ($stringValue[0] === '-')) {
                return RuleCheckResult::make(false, $value, self::ERR_MUST_BE_POSITIVE);
            }

            return RuleCheckResult::make(true, $stringValue);
        }

        return RuleCheckResult::make(false, $value, self::ERR_NOT_A_NUMBER);
    }

}
