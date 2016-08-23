<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\RequiredRule;
use Flowrange\Validation\Rule as r;


/**
 * RequiredRule tests
 */
class RequiredRuleTest extends RuleTestCase
{


    /**
     * MFW_Validation_RuleTestCase::ruleTestProvider
     */
    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\required())

            ->areValid()

                ->test('string')
                    ->value('string')

                ->test('integer')
                    ->value(123)

                ->test('zero')
                    ->value(0)

                ->test('float')
                    ->value(1.23)

                ->test('bool (true)')
                    ->value(true)

                ->test('array')
                    ->value([1, 2, 3])

            ->areNotValid()

                ->test('null')
                    ->value(null)
                    ->error(RequiredRule::ERR_MISSING_VALUE)

                ->test('empty string')
                    ->value('')
                    ->error(RequiredRule::ERR_MISSING_VALUE)

                ->test('false')
                    ->value(false)
                    ->error(RequiredRule::ERR_MISSING_VALUE)

                ->test('empty array')
                    ->value([])
                    ->error(RequiredRule::ERR_MISSING_VALUE);
    }


}