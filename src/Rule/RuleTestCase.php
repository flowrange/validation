<?php

namespace Flowrange\Validation\Rule;

use Flowrange\Validation\Validator;
use Flowrange\Validation\Rule;


/**
 * Base testcase for Rules
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 * @copyright Copyright © 2015 Florent Geffroy
 * @license See license.md
 * @package flowrange/validation
 * @version $Id$
 */
abstract class RuleTestCase extends \PHPUnit_Framework_TestCase
{


    /**
     * Assert that a rule is valid
     */
    public function assertRuleValid(Rule $rule, $value, Validator $validator = null)
    {
        if ($validator == null) {
            $validator = new Validator();
        }
        $this->assertTrue($rule->isValid($value, $validator));
    }


    /**
     * Assert that a rule is not valid
     */
    public function assertRuleNotValid(Rule $rule, $value, Validator $validator = null)
    {
        if ($validator == null) {
            $validator = new Validator();
        }
        $this->assertFalse($rule->isValid($value, $validator));
    }


    /**
     * Assert that getValue returns a specific value
     */
    public function assertGetValueEquals(Rule $rule, $expected)
    {
        $this->assertSame($expected, $rule->getValue());
    }


    /**
     * data provider for testRule
     */
    public function ruleTestProvider()
    {
        $dataBuilder = $this->initDataBuilder();

        if (!$dataBuilder instanceof \Flowrange\Validation\Rule\RuleTestCase\DataBuilder) {
            throw new \Exception('initDataBuilder should return a valid DataBuilder');
        }

        return $this->initDataBuilder()->build();
    }



    /**
     * Returns a DataBuilder
     * @return MFW_Validation_RuleTestCase_DataBuilder
     */
    public function getDataBuilder(Rule $defaultRule = null)
    {
        $dataBuilder = new RuleTestCase\DataBuilder();

        if ($defaultRule) {
            $dataBuilder->defaultRule($defaultRule);
        }

        return $dataBuilder;
    }


    /**
     * Inits and returns a data builder
     */
    abstract public function initDataBuilder();


    /**
     * @dataProvider ruleTestProvider
     */
    public function testRule(Rule $rule, $value, $isValid, $expectedValue, $error, $forwardNext, Validator $validator)
    {
        $expectedResult = new \Flowrange\Validation\RuleCheckResult(
            $isValid, $expectedValue, $error, $forwardNext);

        $result = $rule->check($value, $validator);

        $this->assertEquals(
            $expectedResult,
            $rule->check($value, $validator));

        $this->assertSame(
            $expectedValue,
            $result->getValue());
    }

}