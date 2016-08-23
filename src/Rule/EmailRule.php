<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Email validation
 *
 * * Even if invalid format, the rule forwards to next rule
 * * Domain after @ do not need to have a tld
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class EmailRule extends Rule
{


    /**
     * Error : wrong format
     * @var string
     */
    const ERR_WRONG_FORMAT = 'email.wrong-format';


    /**
     * Email validation regexp
     * @var string
     */
    const EMAIL_REGEXP = '/^[\w!#$%&\'*+\/=?^`{|}~.-]+@(?:[a-z\d][a-z\d-]*(?:\.[a-z\d][a-z\d-]*)?)+(\.(?:[a-z][a-z\d-]+))?$/iD';


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        return RuleCheckResult::make(
            preg_match(self::EMAIL_REGEXP, $value),
            $value,
            self::ERR_WRONG_FORMAT,
            true);
    }

}
