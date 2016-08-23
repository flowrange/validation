<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;

use Flowrange\Validation\Rule\HourRule;
use function \Flowrange\Validation\Rule\hour;

/**
 * Description of HourRule
 *
 * @author Florent
 */
class HourRuleTest extends RuleTestCase
{

    public function initDataBuilder()
    {
        return $this->getDataBuilder(hour())

            ->areValid()

                ->test('hour with : separator')
                    ->value('12:15')
                    ->returns('12:15')

                ->test('hour with h separator')
                    ->value('12h15')
                    ->returns('12:15')

                ->test('hour with one digit')
                    ->value('1h5')
                    ->returns('01:05')

                ->test('only hour as integer')
                    ->value('12')
                    ->returns('12:00')

                ->test('only hour with : separator')
                    ->value('12:')
                    ->returns('12:00')

                ->test('only hour with h separaror')
                    ->value('12h')
                    ->returns('12:00')

            ->areNotValid()

                ->test('string')
                    ->value('aaa')
                    ->error(HourRule::ERR_WRONG_FORMAT)

                ->test('float')
                    ->value('1.2')
                    ->error(HourRule::ERR_WRONG_FORMAT)

                ->test('no hour')
                    ->value('h15')
                    ->error(HourRule::ERR_WRONG_FORMAT)

                ->test('only minutes')
                    ->value(':15')
                    ->error(HourRule::ERR_WRONG_FORMAT)

                ->test('only separator (h)')
                    ->value('h')
                    ->error(HourRule::ERR_WRONG_FORMAT)

                ->test('only separator (:)')
                    ->value(':')
                    ->error(HourRule::ERR_WRONG_FORMAT);
    }

}
