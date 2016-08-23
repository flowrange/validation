<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Rule;
use Flowrange\Validation\Validator;
use Flowrange\Validation\RuleCheckResult;

/**
 * Hour rule
 *
 * * Normalized format : hh:mm (with leading zeros)
 * * The middle separator can be either ':' or 'h'
 * * Minutes part is optional
 * * Each part may have only one digit
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class HourRule extends Rule
{


    /**
     * Error : wrong format
     * @var string
     */
    const ERR_WRONG_FORMAT = 'hour.wrong-format';


    /**
     * Hours regexp
     * @var string
     */
    const HOUR_REGEXP = '/^(\d{1,2})[h:]?(\d{1,2})?$/';


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $valid      = false;
        $pregResult = [];

        if (preg_match(self::HOUR_REGEXP, $value, $pregResult)) {

            $hours   = str_pad($pregResult[1], 2, '0', STR_PAD_LEFT);
            $minutes = '00';
            if (isset($pregResult[2])) {
                $minutes = str_pad($pregResult[2], 2, '0', STR_PAD_LEFT);
            }

            $valid = true;
            $value = "$hours:$minutes";
        }

        return RuleCheckResult::make($valid, $value, self::ERR_WRONG_FORMAT);
    }

}
