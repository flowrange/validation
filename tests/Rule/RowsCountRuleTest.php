<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Rule\RowsCountRule;
use Flowrange\Validation\Rule as r;


/**
 * RowsCount tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 * @copyright Copyright © 2015 Florent Geffroy
 * @license See license.md
 * @package flowrange/validation
 * @version $Id$
 */
class RowsCountRuleTest extends RuleTestCase
{


    public function getRuleClassName()
    {
        return 'Flowrange\Validation\Rule\RowsCountRule';
    }


    /**
     * RuleTestCase::ruleTestProvider
     */
    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\rowsCount())

            ->areValid()

                ->test('array')
                ->value([1, 2, 3])

                ->test('null value')
                ->value(null)->returns([])

                ->test('empty string')
                ->value('')->returns([])

                ->test('less than max')
                ->withRule(r\rowsCount(5))
                ->value([1, 2, 3])

                ->test('more than min')
                ->withRule(r\rowsCount(1, 0))
                ->value([1, 2, 3])

                ->test('as much as min')
                ->withRule(r\rowsCount(2, 0))
                ->value([1, 2])

                ->test('between min and max')
                ->withRule(r\rowsCount(1, 5))
                ->value([1, 2, 3])

                ->test('as much as max')
                ->withRule(r\rowsCount(1, 3))
                ->value([1, 2, 3])

            ->areNotValid()

                ->test('not an array')
                ->value('string')
                ->error(RowsCountRule::ERR_NOT_AN_ARRAY)

                ->test('more than max')
                ->withRule(r\rowsCount(2))
                ->value([1, 2, 3])
                ->error(RowsCountRule::ERR_TOO_MUCH_ROWS)

                ->test('less than min')
                ->withRule(r\rowsCount(2, 0))
                ->value([1])
                ->error(RowsCountRule::ERR_NOT_ENOUGH_ROWS);
    }

}
