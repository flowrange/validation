<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Rule;
use Flowrange\Validation\Validator;
use Flowrange\Validation\RuleCheckResult;

/**
 * Rule to mark a field as optional
 *
 * * This rule do not try to trim or process the value whatsoever (use the sanitize filter before for example)
 * * If the value is empty, it returns a valid RuleCheckResult, but with forwardNext set as false
 * * Empty array and boolean false are considered empty as well
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class OptionalRule extends Rule
{


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $forwardToNext  = ($value !== null && $value !== '' && $value !== false);
        $forwardToNext &= (is_array($value) ? count($value) > 0 : true);

        return new RuleCheckResult(true, $value, '', $forwardToNext);
    }

}
