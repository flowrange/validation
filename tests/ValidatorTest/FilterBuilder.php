<?php

namespace Flowrange\Validation\Tests\ValidatorTest;

/**
 * Builds mockable MFW_Validation_Filter objects
 *
 * @author $Author: florent $
 * @copyright Copyright © 2010 Médiagonale SARL
 * @package MFW_Validation_
 * @subpackage test
 * @since 1.4
 * @version $Id: FilterBuilder.php 2236 2010-11-02 14:32:46Z florent $
 */
class FilterBuilder
{


    /**
     * Parent test
     * @var \PHPUnit_Framework_TestCase
     */
    private $test;


    /**
     * Mock class name
     * @var string
     */
    private $className = '';


    /**
     * filter result
     * @var mixed
     */
    private $filterResult;


    /**
     * Expected value for filter
     * @var mixed
     */
    private $expected;


    /**
     * If the rule should be called
     * @var bool
     */
    private $shouldBeCalled = true;


    /**
     * Constructor
     */
    public function __construct(\PHPUnit_Framework_TestCase $test)
    {
        $this->test = $test;
    }


    /**
     * Sets the mock class name
     * @param string $className
     */
    public function withClassName($className)
    {
        $this->className = $className;
        return $this;
    }


    /**
     * Result returned by filter
     */
    public function yields($value)
    {
        $this->filterResult = $value;
        return $this;
    }


    /**
     * Expects a call to filter with the specified value
     * @param mixed $expected
     */
    public function expects($value)
    {
        $this->expected = $value;
        return $this;
    }


    /**
     * Tells the filter that its filter method should not be called
     */
    public function shouldNotBeCalled()
    {
        $this->shouldBeCalled = false;
        return $this;
    }


    /**
     * Returns a filter mock
     */
    public function build()
    {
        $mock = $this->test->getMock(
            'Flowrange\Validation\Filter',
            array('filter'), array(), $this->className);


        if (!$this->shouldBeCalled) {
            $mock->expects($this->test->never())
                 ->method('filter');

            return $mock;
        }

        $expectation = $mock
            ->expects($this->test->any())
            ->method('filter');

        if($this->expected) {
            $expectation->with($this->expected);
        }
        $expectation
            ->willReturn($this->filterResult);

        return $mock;
    }


}