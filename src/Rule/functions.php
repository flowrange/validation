<?php

namespace Flowrange\Validation\Rule;

/**
 * Returns a new collection of rules
 * @return RuleCollection
 */
function rules(...$rules)
{
    return new \Flowrange\Validation\RuleCollection($rules);
}


/**
 * Check that a value contains multiple rows
 *
 * @param int $minOrMax Max number of rows if one number provided. Min number if 2 numbers provided
 * @param int $max      Max number of rows if first param set
 *
 * @return RowsRule
 */
function rowsCount($minOrMax = null, $max = null)
{
    return new RowsCountRule($minOrMax, $max);
}


/**
 *
 * @return \Flowrange\Validation\Rule\CallbackRule
 */
function callback($callback)
{
    return new CallbackRule($callback);
}


/**
 *
 * @return \Flowrange\Validation\Rule\DateRule
 */
function date()
{
    return new DateRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\EmailRule
 */
function email()
{
    return new EmailRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\FileRule
 */
function file()
{
    return new FileRule();
}


/**
 *
 * @param type
 * @return \Flowrange\Validation\Rule\HourRule
 */
function hour()
{
    return new HourRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\FloatRule
 */
function number()
{
    return new NumberRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\ImageRule
 */
function image()
{
    return new ImageRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\InArrayRule
 */
function inArray(array $allowedValue = [])
{
    return new InArrayRule($allowedValue);
}


/**
 *
 * @return type
 */
function integer()
{
    return new IntegerRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\LengthRule
 */
function length($minOrMax = null, $max = null)
{
    return new LengthRule($minOrMax, $max);
}


/**
 *
 * @return \Flowrange\Validation\Rule\OptionalRule
 */
function optional()
{
    return new OptionalRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\MoneyRule
 */
function money()
{
    return new MoneyRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\RangeRule
 */
function range($min, $max)
{
    return new RangeRule($min, $max);
}


/**
 *
 * @return \Flowrange\Validation\Rule\RegexpRule
 */
function regexp($regexp)
{
    return new RegexpRule($regexp);
}


/**
 *
 * @return \Flowrange\Validation\Rule\RequiredRule
 */
function required()
{
    return new RequiredRule();
}


/**
 *
 * @param type $errorsMessage
 * @return \Flowrange\Validation\Rule\SameAsRule
 */
function sameAs()
{
    return new SameAsRule();
}


/**
 *
 * @return \Flowrange\Validation\Rule\TextRule
 */
function text()
{
    return new TextRule();
}

function singleLineText()
{
    return rules(text(), \Flowrange\Validation\Filter\sanitize()->keepLines(false));
}

function multiLineText()
{
    return rules(text(), \Flowrange\Validation\Filter\sanitize()->keepLines(true));
}


/**
 *
 * @return \Flowrange\Validation\Rule\ValueRule
 */
function value($allowedValue)
{
    return new ValueRule($allowedValue);
}