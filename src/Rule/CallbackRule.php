<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Callback validation
 *
 * * The callback must return a RuleCheckResult object
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class CallbackRule extends Rule
{


    /**
     * Callback
     * @var callback
     */
    private $callback;


    /**
     * Constructor
     *
     * @param callback $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }


    /**
     * Rule::check
     */
    public function check($value, Validator $validator)
    {
        $result = call_user_func($this->callback, $value, $validator);

        return $result;
    }

}
