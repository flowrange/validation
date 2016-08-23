<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Rule;
use Flowrange\Validation\Validator;
use Flowrange\Validation\RuleCheckResult;

/**
 * Money validation
 *
 * * It extends NumberRule
 * * Takes a float value (with 2 digits after decimal separator)
 * * Decimal separator may be ',' or '.'
 * * Gets converted to an int : '12.34' -> 1234
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class MoneyRule extends NumberRule
{


    /**
     * Error : not a price
     * @var string
     */
    const ERR_NOT_A_PRICE = 'money.not-a-price';


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $parentResult = parent::check($value, $validator);

        if ($parentResult->isValid()) {

            $value    = $parentResult->getValue();
            $parts    = array_pad(preg_split('/[.]/', $value), 2, 0);
            $parts[1] = str_pad($parts[1], 2, '0', STR_PAD_RIGHT);

            $value = (int)$parts[0] * 100 + $parts[1];

            return RuleCheckResult::make(true, $value);
        }

        return $parentResult;
    }

}
