<?php

namespace Flowrange\Validation\Tests\ValidatorTest;

use Flowrange\Validation\Rule;
use Flowrange\Validation\RuleCheckResult;

/**
 * Builds mockable Rule objects
 *
 * @author Florent Geffroy <contact@flowrange.fr>
 */
class RuleBuilder
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
     * isValid result
     * @var bool
     */
    private $isValidResult;


    /**
     * Expected value for check()
     * @var mixed
     */
    private $expected = array();


    /**
     * Param for the returned RuleCheckResult
     *
     * @var mixed
     */
    private $yield = array();


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
     * Expects a call to check with the specified value
     *
     * @param mixed $value
     */
    public function expects(...$values)
    {
        foreach ($values as $value) {
            $this->expected[] = $value;
        }
        return $this;
    }


    /**
     * Result returned by the RuleCheckResult
     *
     * @param mixed $values
     */
    public function yields($valid = true, $value = null, $error = '', $forwardNext = true)
    {
        $this->yield[] = [$valid, $value, $error, $forwardNext];
        return $this;
    }


    /**
     * Tells the rule that its check method should not be called
     */
    public function shouldNotBeCalled()
    {
        $this->shouldBeCalled = false;
        return $this;
    }


    /**
     * Returns a rule mock
     */
    public function build()
    {
        $mock = $this->test->getMock(
            Rule::class,
            array('check'), [], $this->className);


        if (!$this->shouldBeCalled) {

            $mock->expects($this->test->never())
                 ->method('check');

            return $mock;
        }

        if (count($this->expected) > 0) {

            foreach ($this->expected as $index => $expected) {

                if (!isset($this->yield[$index])) {
                    $this->yield[$index] = [true, $expected, '', true];
                }

                list($valid, $value, $error, $forwardNext) = $this->yield[$index];
                if ($value === null) {
                    $value = $expected;
                }

                $mock
                    ->expects($this->test->at($index))
                    ->method('check')
                    ->with($expected)
                    ->willReturn(new RuleCheckResult($valid, $value, $error, $forwardNext));
            }

            $mock->expects($this->test->exactly(count($this->expected)))
                 ->method('check');

        } else {

            $result = null;
            if(isset($this->yield[0])) {
                $result = $this->yield[0];
            } else {
                $result = [true, '', '', true];
            }

            list($valid, $value, $error, $forwardNext) = $result;
            if ($value === null) {
                $value = '';
            }

            $mock
                ->expects($this->test->any())
                ->method('check')
                ->willReturn(new RuleCheckResult($valid, $value, $error, $forwardNext));
        }

        return $mock;
    }

}
