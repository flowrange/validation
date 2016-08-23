<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Rule\EmailRule;
use Flowrange\Validation\Rule as r;


/**
 * EmailRule tests
 */
class EmailRuleTest extends RuleTestCase
{


    public function initDataBuilder()
    {
        return $this->getDataBuilder(r\email())

            ->areValid()

                ->test('simple email')
                    ->value('test@domain.com')

                ->test('special chars')
                    ->value('test!#$%&\'*+-/=?^_`.{|}~test@domain.com')

                ->test('no tld')
                    ->value('test@domain')

            ->areNotValid()

                ->test('No @')
                    ->value('test')->error(EmailRule::ERR_WRONG_FORMAT)
                    ->forwardsToNext()

                ->test('Nothing after @')
                    ->value('test@')
                    ->error(EmailRule::ERR_WRONG_FORMAT)
                    ->forwardsToNext()

                ->test('Nothing before @')
                    ->value('@test.com')
                    ->error(EmailRule::ERR_WRONG_FORMAT)
                    ->forwardsToNext();
    }

}
