<?php

namespace Flowrange\Validation\Tests\Rule;

use Flowrange\Validation\Rule\RuleCollection;
use Flowrange\Validation\Rule;
use Flowrange\Validation\Filter;

/**
 * RuleCollection test case
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RuleCollectionTest extends \PHPUnit_Framework_TestCase
{


    public function testConstructor()
    {
        $rules = [
            $this->getMock(Rule::class),
            $this->getMock(Filter::class)
        ];

        $ruleCollection = new RuleCollection($rules);

        $this->assertInstanceOf(
            RuleCollection::class,
            $ruleCollection);

        $this->assertSame(
            $rules,
            $ruleCollection->getRules());
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testConstructorChecksThatPassedRulesAreRuleOrFilterInstances()
    {
        $rules = [
            $this->getMock(Rule::class),
            $this->getMock(Filter::class),
            'invalid rule'
        ];

        new RuleCollection($rules);
    }


    public function testUsingArraysAllowsForConditionalInclusion()
    {
        $rule1 = $this->getMock(Rule::class);
        $rule2 = $this->getMock(Rule::class);
        $rule3 = $this->getMock(Rule::class);

        $ruleCollection = new RuleCollection([
            $rule1,
            [false, $rule2],
            [true,  $rule3]
        ]);

        $this->assertSame(
            [
                $rule1,
                $rule3
            ],
            $ruleCollection->getRules());
    }


    public function testCollectionsAreMerged()
    {
        $rule1 = $this->getMock(Rule::class);
        $rule2 = $this->getMock(Rule::class);
        $rule3 = $this->getMock(Rule::class);
        $rule4 = $this->getMock(Rule::class);

        $ruleCollection1 = new RuleCollection([$rule2, $rule3]);
        $ruleCollection2 = new RuleCollection([$rule1, $ruleCollection1, $rule4]);

        $this->assertSame(
            [
                $rule1,
                $rule2,
                $rule3,
                $rule4
            ],
            $ruleCollection2->getRules());
    }

}
