<?php

namespace Flowrange\Validation;

/**
 * Base class for validation rules
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
abstract class Rule
{


    /**
     * Constructor
     */
    public function __construct()
    {
    }


    /**
     * Check a value
     *
     * Implementors must return a RuleValidationResult instance. It will tell the parent collection whether the
     * validation passed or failed, and whether it may process the next rule/filter
     *
     * Whatever the rule does to the value (change it or leave it as-is), it must be put in the RuleValidationResult in
     * the data property
     *
     * @param mixed     $value
     * @param Validator $validator
     * @param mixed     $next
     *
     * @return RuleCheckResult
     */
    public abstract function check($value, Validator $validator);

}