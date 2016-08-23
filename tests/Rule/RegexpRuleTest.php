<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\RegexpRule;
use Flowrange\Validation\Rule as r;


/**
 * RegexpRule tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RegexpRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\regexp())

            ->areValid()

                ->test('value matches regexp')
                    ->value('value')
                    ->withRule(r\regexp('/^value$/'))

            ->areNotValid()

                ->test('value doesnt match regexp')
                    ->value('not valid')
                    ->withRule(r\regexp('/^other$/'))
                    ->error(RegexpRule::ERR_DOESNT_MATCH);
    }

}