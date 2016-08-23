<?php

namespace Flowrange\Validation;

use Flowrange\Validation\Rule;
use Flowrange\Validation\Filter;


/**
 * Collection of rules/filters
 *
 * The collection handle a chain of rules and filters in a chain of command fashion. Each rule may stop the chain or
 * allow to continue.
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RuleCollection
{


    /**
     * List of rules/filters
     * @var (Rule|Filter)[]
     */
    private $rules;


    /**
     * Returns the list of rules/filters
     * @return (Rule|Filter)[]
     */
    public function getRules()
    {
        return $this->rules;
    }


    /**
     * Constructor
     *
     * Rules may be conditionnally included, use an array :
     * <code>
     *     new RuleCollection([
     *          $rule1,
     *          [true,  $rule2], // Will be included
     *          [false, $rule3]  // Will not be included
     *     ]);
     * </code>
     *
     * @param (Rule|Filter)[] $rules List of rules/filters
     */
    public function __construct(array $rules = [])
    {
        $this->rules = [];

        foreach ($rules as $ruleOrArray) {

            if ($ruleOrArray instanceof RuleCollection) {

                $this->rules = array_merge($this->rules, $ruleOrArray->getRules());

            } else {

                $rule = null;

                if (is_array($ruleOrArray)) {
                    if ($ruleOrArray[0] === true) {
                        $rule = $ruleOrArray[1];
                    } else {
                        continue;
                    }
                } else {
                    $rule = $ruleOrArray;
                }

                if ($rule instanceof Rule || $rule instanceof Filter) {
                    $this->rules[] = $rule;
                } else {
                    throw new \InvalidArgumentException(
                        'Passed objects must be instances of Rule or Filter');
                }
            }
        }
    }

}
