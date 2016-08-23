<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Regular Expression validation
 *
 * * Simple wrapper for preg_match
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RegexpRule extends Rule
{


    /**
     * Error : regexp doesn't match value
     * @var string
     */
    const ERR_DOESNT_MATCH = 'regexp.doesnt-match';


    /**
     * Regexp
     * @var string
     */
    private $regexp;


    /**
     * Constructeur
     * @param string $regexp Regular expression
     */
    public function __construct($regexp)
    {
        $this->regexp = $regexp;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        return RuleCheckResult::make(
            preg_match($this->regexp, $value),
            $value,
            self::ERR_DOESNT_MATCH
        );
    }

}
