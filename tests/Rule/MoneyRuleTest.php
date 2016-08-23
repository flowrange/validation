<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Rule\MoneyRule;
use function Flowrange\Validation\Rule\money;

/**
 * MoneyRule test
 *
 * @author Florent
 */
class MoneyRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {

        return $this->getDataBuilder(money())

            ->areValid()

                ->test('no cents')
                    ->value('5')
                    ->returns(500)

                ->test('with cents')
                    ->value('100.45')
                    ->returns(10045)

                ->test('only cents')
                    ->value('.45')
                    ->returns(45)

                ->test('no cents, but decimal separator')
                    ->value('10.')
                    ->returns(100);

        // no invalid rules, covered by NumberRule
    }

}
