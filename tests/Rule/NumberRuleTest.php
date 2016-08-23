<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\NumberRule;
use Flowrange\Validation\Rule as r;


/**
 * NumberRule tests
 */
class NumberRuleTest extends RuleTestCase
{


    /**
     * MFW_Validation_RuleTestCase::ruleTestProvider
     */
    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\number())

            ->areValid()

                ->test('float')
                    ->value(123.456789)
                    ->returns('123.456789')

                ->test('precision may be limited')
                    ->value(123.456789)
                    ->withRule(r\number()->precision(3))
                    ->returns('123.457')

                ->test('double')
                    ->value((double)123.456789)
                    ->returns('123.456789')

                ->test('negative float (if allowed)')
                    ->value(-123.456789)
                    ->withRule(r\number()->allowNegative())
                    ->returns('-123.456789')

                ->test('float in string (dot)')
                    ->value('123.456789')

                ->test('float in string (comma)')
                    ->value('123,456789')
                    ->returns('123.456789')

                ->test('rounding works with strings')
                    ->value('123.456789')
                    ->withRule(r\number()->precision(3))
                    ->returns('123.457')

                ->test('float without integer part')
                    ->value('.123')
                    ->returns('0.123')

                ->test('float without real part')
                    ->value('123.')
                    ->returns('123')

                ->test('float with trailing zeros')
                    ->value('123.0000')
                    ->returns('123')

                ->test('negative in string (if allowed)')
                    ->value('-123.456789')
                    ->withRule(r\number()->allowNegative())

                ->test('integer')
                    ->value(123)
                    ->returns('123')

                ->test('integer as string')
                    ->value('123')

                ->test('null is left as-is')
                    ->value(null)

                ->test('empty string is changed to null')
                    ->value('')
                    ->returns(null)

                ->test('null is changed to 0 if told')
                    ->value(null)
                    ->withRule(r\number()->keepEmpty(false))
                    ->returns('0')

                ->test('empty string is changed to 0 if told')
                    ->value('')
                    ->withRule(r\number()->keepEmpty(false))
                    ->returns('0')

            ->areNotValid()

                ->test('string')
                    ->value('string')
                    ->error(NumberRule::ERR_NOT_A_NUMBER)

                ->test('boolean')
                    ->value(true)
                    ->error(NumberRule::ERR_NOT_A_NUMBER)

                ->test('float with unknow separator')
                    ->value('123a456')
                    ->error(NumberRule::ERR_NOT_A_NUMBER)

                ->test('array')
                    ->value([1, 2, 3])
                    ->error(NumberRule::ERR_NOT_A_NUMBER)

                ->test('object')
                    ->value($this)
                    ->error(NumberRule::ERR_NOT_A_NUMBER)

                ->test('negative float')
                    ->value(-123.456789)
                    ->error(NumberRule::ERR_MUST_BE_POSITIVE)

                ->test('negative integer')
                    ->value(-123)
                    ->error(NumberRule::ERR_MUST_BE_POSITIVE)

                ->test('negative in string')
                    ->value('-123.456789')
                    ->error(NumberRule::ERR_MUST_BE_POSITIVE);
    }


}