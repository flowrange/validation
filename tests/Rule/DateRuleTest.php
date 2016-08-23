<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule\DateRule;
use Flowrange\Validation\Rule as r;


/**
 * MFW_Validation_Rule_Date tests
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage Rule_test
 * @since 1.4
 * @version $Id: DateTest.php 2234 2010-10-28 13:32:13Z florent $
 */
class DateRuleTest extends RuleTestCase
{


    /**
     * MFW_Validation_RuleTestCase::ruleTestProvider
     */
    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\date())

            ->areValid()

                ->test('normal date')
                    ->value('27/12/1983')
                    ->returns('1983-12-27')

                ->test('single digit year')
                    ->value('27/12/3')
                    ->returns(substr(date('Y'), 0, 2) . '03-12-27')

                ->test('double digits year')
                    ->value('27/12/83')
                    ->returns('1983-12-27')

                ->test('double digits year (with leading 0)')
                    ->value('27/12/03')
                    ->returns('2003-12-27')

                ->test('3-digits year')
                    ->value('27/12/983')
                    ->returns('0983-12-27')

                ->test('no year')
                    ->value('27/12')
                    ->returns(date('Y') . '-12-27')

                ->test('single digit months')
                    ->value('2/1/2010')
                    ->returns('2010-01-02')

            ->areNotValid()

                ->test('string')
                    ->value('abcdef')
                    ->error(DateRule::ERR_WRONG_FORMAT)

                ->test('int')
                    ->value('27')
                    ->error(DateRule::ERR_WRONG_FORMAT)

                ->test('invalid day')
                    ->value('32/02/2010')
                    ->error(DateRule::ERR_INVALID_DATE)

                ->test('invalid month')
                    ->value('02/15/2010')
                    ->error(DateRule::ERR_INVALID_DATE);
    }


}