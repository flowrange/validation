<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Integer validation
 *
 * * A string may pass if it contains digits
 * * bools pass
 * * floats do not pass
 * * The value is cast to an int if valid
 * * If keepEmpty is true (by default), empty values ('' or null) are returned as null, and not cast as int. This allows one to
 *   differentiate empty and 0
 * * If keepEmpty is false, empty values ('' or null) are changed to 0
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class IntegerRule extends Rule
{


    /**
     * Error : not an integer
     * @var string
     */
    const ERR_NOT_AN_INTEGER = 'integer.not-an-integer';


    /**
     * Error : the value must be positive
     * @var string
     */
    const ERR_MUST_BE_POSITIVE = 'integer.must-be-positive';


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
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        if ($value === '' || $value === null) {
            return RuleCheckResult::make(true, ($this->keepEmpty ? null : (int)$value));
        }

        $valid =
               is_int($value)
            || (is_string($value) && preg_match('/^-?[0-9]+$/', $value))
            || is_bool($value);

        if($valid) {

            if (!$this->allowNegative && ((int)$value < 0)) {
                return RuleCheckResult::make(false, $value, self::ERR_MUST_BE_POSITIVE);
            }

            return RuleCheckResult::make(true, (int)$value);
        }

        return RuleCheckResult::make(false, $value, self::ERR_NOT_AN_INTEGER);
    }

}
