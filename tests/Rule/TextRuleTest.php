<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\TextRule;
use Flowrange\Validation\Rule as r;


/**
 * TextRule test case
 *
 * @author    Florent Geffroy <contact@flowrange.fr>
 * @copyright Copyright © 2016 Florent Geffroy
 * @license   See license.md
 * @package   flowrange/validation
 */
class TextRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\text())

            ->areValid()

                ->test('simple string')
                    ->value('string')

                ->test('integer (cast as string)')
                    ->value(123)
                    ->returns('123')

                ->test('float (cast as string)')
                    ->value(1.23)
                    ->returns('1.23')

                ->test('null (changed to empty string)')
                    ->value(null)
                    ->returns('')

                ->test('empty string')
                ->value('')

                ->test('true (changed to one)')
                    ->value(true)
                    ->returns('1')

                ->test('false (changed to empty string)')
                    ->value(false)
                    ->returns('')

            ->areNotValid()

                ->test('array')
                    ->value(['key' => 'value'])
                    ->error(TextRule::ERR_NOT_A_STRING)
                    ->returns('')

                ->test('object')
                    ->value($this)
                    ->error(TextRule::ERR_NOT_A_STRING)
                    ->returns('');
    }

}
