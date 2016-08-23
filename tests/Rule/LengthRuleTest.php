<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\LengthRule;
use Flowrange\Validation\Rule as r;


/**
 * LengthRule tests
 */
class LengthRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\length())

            ->areValid()

                ->test('valid by default')
                    ->value('string1')

                ->test('withing boundaries')
                    ->value('string2')
                    ->withRule(r\length(2, 10))

                ->test('min = max')
                    ->value('string3')
                    ->withRule(r\length(7, 7))

                ->test('more than min')
                    ->value('string4')
                    ->withRule(r\length()->min(7))

                ->test('less than max')
                    ->value('string5')
                    ->withRule(r\length(10))

                ->test('multi-byte utf-8 chars count as 1 char')
                    ->value('ééé')
                    ->withRule(r\length(3, 3))

                ->test('can check integer digit length')
                    ->value(12345)
                    ->withRule(r\length()->min(2)->max(10))

            ->areNotValid()

                ->test('string too long')
                    ->value('string1')
                    ->withRule(r\length(5))
                    ->error(LengthRule::ERR_TOO_LONG)
                    ->forwardsToNext()

                ->test('string too short')
                    ->value('string2')
                    ->withRule(r\length(10, 15))
                    ->error(LengthRule::ERR_TOO_SHORT)
                    ->forwardsToNext()

                ->test('integer too long')
                    ->value(1234561)
                    ->withRule(r\length(0, 3))
                    ->error(LengthRule::ERR_TOO_LONG)
                    ->forwardsToNext()

                ->test('integer too short')
                    ->value(1234562)
                    ->withRule(r\length(10, 15))
                    ->error(LengthRule::ERR_TOO_SHORT)
                    ->forwardsToNext();
    }

}
