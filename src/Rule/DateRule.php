<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Date validation
 *
 * * Normalized to YYYY-MM-DD
 * * Takes a string formatted as DD/MM/YYYY
 * * All part may have a variable number of digits
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class DateRule extends Rule
{


    /**
     * Date parsing regexp
     * @var string
     */
    const DATE_REGEXP = '#^(\d{1,2})/(\d{1,2})(?:/(\d{1,4}))?$#iuD';


    /**
     * Error : wrong format
     * @var string
     */
    const ERR_WRONG_FORMAT = 'date.wrong-format';


    /**
     * Error : date is invalid
     * @var string
     */
    const ERR_INVALID_DATE = 'date.invalid-date';


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $valid        = false;
        $error        = self::ERR_WRONG_FORMAT;

        $iso8601Value = $value;

        $result = array();
        if(preg_match(self::DATE_REGEXP, $value, $result)) {

            $day   = $result[1];
            $month = $result[2];
            $year  = isset($result[3]) ? $result[3] : \date('Y');

            try {

                $valid        = true;
                $error        = '';
                $iso8601Value = (new \DateTime("$year-$month-$day 00:00:00"))->format('Y-m-d');

            } catch (\Exception $ex) {

                $valid = false;
                $error = self::ERR_INVALID_DATE;
            }
        }

        return RuleCheckResult::make($valid, $iso8601Value, $error);
    }

}
