<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Checks that a value is a string
 *
 * * All scalars vars are considered valid (string, bool, int, float)
 * * The value is cast to a string if valid
 * * A null value is changed to an empty string
 * * If the value is not a string, we don't forward to next rules
 *
 * @author    Florent Geffroy <contact@flowrange.fr>
 * @copyright Copyright © 2016 Florent Geffroy
 * @license   See license.md
 * @package   flowrange/validation
 */
class TextRule extends Rule
{


    /**
     * Error : value is not a string
     * @var string
     */
    const ERR_NOT_A_STRING = 'text.not-a-string';


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        if ($value === null || is_scalar($value)) {
            return RuleCheckResult::make(true, (string)$value);
        }
        return RuleCheckResult::make(false, '', self::ERR_NOT_A_STRING);
    }

}
