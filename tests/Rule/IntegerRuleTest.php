<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\IntegerRule;
use Flowrange\Validation\Rule as r;


/**
 * MFW_Validation_Rule_Int tests
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Rule_test
 * @since 1.4
 * @version $Id: IntTest.php 2234 2010-10-28 13:32:13Z florent $
 */
class IntegerRuleTest extends RuleTestCase
{


    /**
     * MFW_Validation_RuleTestCase::ruleTestProvider
     */
    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\integer())

            ->areValid()

                ->test('simple integer')
                    ->value(123)

                ->test('integer in string')
                    ->value('123')
                    ->returns(123)

                ->test('bool (true)')
                    ->value(true)
                    ->returns(1)

                ->test('bool (false)')
                    ->value(false)
                    ->returns(0)

                ->test('negative int (if allowed)')
                    ->value(-123)
                    ->withRule(r\integer()->allowNegative())
                    ->returns(-123)

                ->test('negative int in string (if allowed')
                    ->value('-123')
                    ->withRule(r\integer()->allowNegative())
                    ->returns(-123)

                ->test('null is left as-is')
                    ->value(null)
                    ->returns(null)

                ->test('empty string is changed to null')
                    ->value('')
                    ->returns(null)

                ->test('null is changed to 0 if told')
                    ->value(null)
                    ->withRule(r\integer()->keepEmpty(false))
                    ->returns(0)

                ->test('empty string is changed to 0 if told')
                    ->value('')
                    ->withRule(r\integer()->keepEmpty(false))
                    ->returns(0)

            ->areNotValid()

                ->test('string')
                    ->value('string')
                    ->error(IntegerRule::ERR_NOT_AN_INTEGER)

                ->test('float')
                    ->value(1.23)
                    ->error(IntegerRule::ERR_NOT_AN_INTEGER)

                ->test('array')
                    ->value([1, 2, 3])
                    ->error(IntegerRule::ERR_NOT_AN_INTEGER)

                ->test('object')
                    ->value($this)
                    ->error(IntegerRule::ERR_NOT_AN_INTEGER)

                ->test('negative (disallowed by default)')
                    ->value(-123)
                    ->error(IntegerRule::ERR_MUST_BE_POSITIVE);
    }


}