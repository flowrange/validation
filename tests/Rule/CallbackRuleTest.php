<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleTestCase;
use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule as r;
use Flowrange\Validation\RuleCheckResult;

/**
 * CallbackRule tests
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class CallbackRuleTest extends RuleTestCase
{


    private $exampleValidator;


    public function initDataBuilder()
    {
        $this->exampleValidator = new Validator();

        return $this->getDataBuilder()

            ->areValid()

                ->test('callback returns valid RuleCheckResult')
                    ->value('value')
                    ->withRule(r\callback([$this, 'callback1']))
                    ->returns('some-value')

                ->test('callback receives validator')
                    ->value('value')
                    ->withRule(r\callback([$this, 'callback2']))
                    ->withValidator($this->exampleValidator)
                    ->returns('some-value')

            ->areNotValid()

                ->test('callback returns invalid RuleCheckResult')
                    ->value('value')
                    ->withRule(r\callback([$this, 'callback3']))
                    ->error('some-error');
    }


    /**
     * Callback 1
     */
    public function callback1($value, Validator $validator)
    {
        return new RuleCheckResult(true, 'some-value', '', true);
    }


    /**
     * Callback 2
     */
    public function callback2($value, Validator $validator)
    {
        $this->assertSame($this->exampleValidator, $validator);

        return new RuleCheckResult(true, 'some-value', '', true);
    }


    /**
     * Callback 3
     */
    public function callback3($value, $options = array())
    {
        return new RuleCheckResult(false, $value, 'some-error', false);
    }

}
