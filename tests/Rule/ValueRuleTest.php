<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\ValueRule;
use Flowrange\Validation\Rule as r;


/**
 * ValueRule tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class ValueRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder()

            ->areValid()

                ->test('value is equal')
                    ->value('value')
                    ->withRule(r\value('value'))

                ->test('value is equal (not strict)')
                    ->value(123)
                    ->withRule(r\value('123')->strict(false))

            ->areNotValid()

                ->test('value is not equal')
                    ->value('value')
                    ->withRule(r\value('other'))
                    ->error(ValueRule::ERR_NOT_ALLOWED)

                ->test('value is equal, but not strict')
                    ->value(123)
                    ->withRule(r\value('123')->strict())
                    ->error(ValueRule::ERR_NOT_ALLOWED);
    }

}