<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\InArrayRule;
use Flowrange\Validation\Rule as r;


/**
 * InArrayRule tests
 */
class InArrayRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder()

            ->areValid()

                ->test('value is in array values')
                ->value('val1')
                ->withRule(
                    r\inArray(['val1', 'val2']))

                ->test('value is in array values (not strict)')
                ->value('123')
                ->withRule(
                    r\inArray([123, 456, 789]))

                ->test('value is in array keys')
                ->value('val1')
                ->withRule(
                    r\inArray(['val1' => '1', 'val2' => '2'])->useKeys())

                ->test('value is in array keys, even with null values')
                ->value('val1')
                ->withRule(
                    r\inArray(['val1' => null, 'val2' => null])->useKeys())

            ->areNotValid()

                ->test('value is not in array values')
                ->value('val1')
                ->withRule(
                    r\inArray(['val2', 'val3']))
                ->error(InArrayRule::ERR_VALUE_NOT_ALLOWED)

                ->test('value is in array values, but not strict')
                ->value('123')
                ->withRule(
                    r\inArray([123, 456, 789])->strict())
                ->error(InArrayRule::ERR_VALUE_NOT_ALLOWED)

                ->test('value is not in array keys')
                ->value('val1')
                ->withRule(
                    r\inArray(['val2' => '2', 'val3' => '3'])->useKeys())
                ->error(InArrayRule::ERR_VALUE_NOT_ALLOWED);
    }

}
