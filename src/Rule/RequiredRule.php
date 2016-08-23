<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Check that a value has something in it
 *
 * * This rule do not try to trim or process the value whatsoever (use the sanitize filter before for example)
 * * The rule checks against null and '' with ===
 * * An int 0 is not considered empty (if you want to disallow 0, use the range rule)
 * * false is considered empty
 * * If the value is an array, it checks that its count() is more than 0
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RequiredRule extends Rule
{


    /**
     * Error : missing value
     * @var string
     */
    const ERR_MISSING_VALUE = 'required.missing-value';


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $valid  = ($value !== null && $value !== '' && $value !== false);
        $valid &= (is_array($value) ? count($value) > 0 : true);

        return RuleCheckResult::make($valid, $value, self::ERR_MISSING_VALUE);
    }

}
