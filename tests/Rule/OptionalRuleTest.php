<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Rule\OptionalRule;
use Flowrange\Validation\Rule as r;


/**
 * OptionalRule tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class OptionalRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\optional())

            ->areValid()

                ->test('value forwards to next')
                    ->value('some value')

                ->test('empty value doesn\'t forward to next')
                    ->value('')
                    ->doesntForwardToNext()

                ->test('empty array doesn\'t forward to next')
                    ->value([])
                    ->doesntForwardToNext()

                ->test('true forwards to next')
                    ->value(true)

                ->test('false doesn\'t forward to next')
                    ->value(false)
                    ->doesntForwardToNext();
    }

}
