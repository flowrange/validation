<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\RangeRule;
use Flowrange\Validation\Rule as r;


/**
 * RangeRule tests
 */
class RangeRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\range())

            ->areValid()

                ->test('valid by default')
                    ->value(5)

                ->test('within range')
                    ->value(5)
                    ->withRule(r\range(2, 10))

                ->test('exact value')
                    ->value(5)
                    ->withRule(r\range(5, 5))

                ->test('more than min')
                    ->value(5)
                    ->withRule(r\range(2))

                ->test('less than max')
                    ->value(5)
                    ->withRule(r\range()->max(10))

                ->test('within range (float)')
                    ->value(19.6*100)
                    ->withRule(r\range(1000.23, 2000.54))

                ->test('same as min (float)')
                    ->value(19.6*100)
                    ->withRule(r\range(1960, 2000.54))

                ->test('same as max (float)')
                    ->value(19.6*100)
                    ->withRule(r\range(1000.23, 1960))

                ->test('same as min with precision taken into account (float)')
                    ->value(19.6*100)
                    ->withRule(r\range(1960.001, 2000)->precision(1))

                ->test('within range (with floats in strings)')
                    ->value('1960.1')
                    ->withRule(r\range('1000.23', '2000.54'))

            ->areNotValid()

                ->test('too low')
                    ->value(5)
                    ->withRule(r\range(10, 15))
                    ->error(RangeRule::ERR_TOO_LOW)

                ->test('too high')
                    ->value(10)
                    ->withRule(r\range(2, 5))
                    ->error(RangeRule::ERR_TOO_HIGH)

                ->test('too low (float with precision)')
                    ->value(19.6*100)
                    ->withRule(r\range(1960.001, 2000.54)->precision(3))
                    ->error(RangeRule::ERR_TOO_LOW)

                ->test('too high (float with precision')
                    ->value(19.6*100)->withRule(r\range(1000.23, 1959.999))
                    ->error(RangeRule::ERR_TOO_HIGH);
    }

}
